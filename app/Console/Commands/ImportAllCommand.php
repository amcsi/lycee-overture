<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Console\Commands;

use amcsi\LyceeOverture\Card;
use amcsi\LyceeOverture\Debug\Profiling;
use amcsi\LyceeOverture\I18n\NameTranslator\DeeplCacheStore;
use amcsi\LyceeOverture\I18n\TranslationUsedTracker;
use amcsi\LyceeOverture\Notifications\GlobalNotifiable;
use amcsi\LyceeOverture\Notifications\NewCardsNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Stopwatch\Stopwatch;

class ImportAllCommand extends Command
{
    public const COMMAND = 'lycee:import-all';

    protected $signature = self::COMMAND .
    ' {--images : Also download images from website and upload to cloudinary}' .
    ' {--lackey : Build LackeyCCG plugin(s)}' .
    ' {--translations : Also download manual translations from OneSky}' .
    ' {--no-cache : Do not use cache for downloading the CSV}';

    protected $description = 'Does importing of the CSV, its data, and auto translations.';

    public function handle()
    {
        $stopwatch = new Stopwatch();
        $stopwatchEvent = $stopwatch->start('import-all');
        $this->output->text('Started doing all the import tasks...');

        $start = now();

        $downloadCsvArguments = [];
        if ($this->option('no-cache')) {
            $downloadCsvArguments['--force'] = true;
        }

        $this->call(DownloadCsvCommand::COMMAND, $downloadCsvArguments);
        $this->call(ImportBasicCardsCommand::COMMAND);
        $this->call(ImportTextsCommand::COMMAND);

        $doTranslationSteps = $this->option('translations');
        if ($doTranslationSteps) {
            try {
                $this->call(DownloadTranslations::COMMAND);
            } catch (\Throwable $exception) {
                $this->warn((string) $exception);
                // Log the warning, but continue execution, because this step is optional.
                Log::warning((string) $exception);
            }
        }

        $this->call(AutoTranslateCommand::COMMAND);

        $actions = [
            fn() => $this->call(DeeplTranslateCommand::COMMAND, ['--locale' => 'en']),
            fn() => $this->call(DeeplTranslateCommand::COMMAND, ['--locale' => 'es']),
            fn() => $this->call(DeeplTranslateCommand::COMMAND, ['--locale' => 'hu']),
        ];

        foreach ($actions as $action) {
            try {
                $action();
            } catch (\Throwable $exception) {
                Log::warning($exception->getMessage());
                report($exception);
            }
        }

        // Ideally this would be done via garbage collection, not manually.
        // These instances should not be configured as singletons in AppServiceProvider.
        app(DeeplCacheStore::class)->flush();
        app(TranslationUsedTracker::class)->flush();

        if ($this->option('images')) {
            $this->call(ImageDownloadCommand::COMMAND, ['--new-only' => true]);
            $this->call(ImageUploadCommand::COMMAND, ['--new-only' => true]);
        }

        if ($doTranslationSteps) {
            try {
                $this->call(UploadTranslations::COMMAND);
            } catch (\Throwable $exception) {
                // Log the warning, but continue execution, because this step is optional.
                Log::warning((string) $exception);
            }
        }

        $this->output->text(
            "Finished doing all the import tasks in " . Profiling::stopwatchToHuman($stopwatchEvent->stop())
        );

        $newCards = Card::where('created_at', '>=', $start)->get();
        if (count($newCards)) {
            app(GlobalNotifiable::class)->notify(new NewCardsNotification($newCards));
        }

        if ($this->option('lackey')) {
            try {
                $this->call(BuildLackeyCommand::COMMAND);
            } catch (\Throwable $exception) {
                Log::warning((string) $exception);
            }
        }

        $this->info(sprintf("Peak memory usage: %.2f MB", memory_get_peak_usage() / 1024 / 1024));
    }
}
