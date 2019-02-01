<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Console\Commands;

use amcsi\LyceeOverture\Debug\Profiling;
use Illuminate\Console\Command;
use Symfony\Component\Stopwatch\Stopwatch;

class ImportAllCommand extends Command
{
    public const COMMAND = 'lycee:import-all';

    protected $signature = self::COMMAND .
    ' {--images : Also download images from website and upload to cloudinary}' .
    ' {--translations : Also download manual translations from OneSky}' .
    ' {--no-cache : Do not use cache for downloading the CSV}';

    protected $description = 'Does importing of the CSV, its data, and auto translations.';

    public function handle()
    {
        $stopwatch = new Stopwatch();
        $stopwatchEvent = $stopwatch->start('import-all');
        $this->output->text('Started doing all the import tasks...');

        $downloadCsvArguments = [];
        if ($this->option('no-cache')) {
            $downloadCsvArguments['--force'] = true;
        }

        $this->call(DownloadCsvCommand::COMMAND, $downloadCsvArguments);
        $this->call(ImportBasicCardsCommand::COMMAND);
        $this->call(ImportTextsCommand::COMMAND);

        if ($this->option('translations')) {
            $this->call(DownloadTranslations::COMMAND);
        }

        $this->call(AutoTranslateCommand::COMMAND);

        if ($this->option('images')) {
            $this->call(ImageDownloadCommand::COMMAND);
            $this->call(ImageUploadCommand::COMMAND);
        }

        $this->output->text(
            "Finished doing all the import tasks in " . Profiling::stopwatchToHuman($stopwatchEvent->stop())
        );
    }
}
