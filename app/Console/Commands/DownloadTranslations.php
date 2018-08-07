<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Console\Commands;

use amcsi\LyceeOverture\Debug\Profiling;
use amcsi\LyceeOverture\I18n\OneSkyClient;
use Illuminate\Console\Command;
use Symfony\Component\Stopwatch\Stopwatch;

class DownloadTranslations extends Command
{
    protected $signature = 'lycee:download-translations';
    protected $description = 'Downloads translatables to OneSky';

    public function handle()
    {
        $stopwatchEvent = (new Stopwatch())->start('download-translations');
        $this->output->text('Started downloading translations from OneSky');

        $result = app(OneSkyClient::class)->downloadTranslations();
        $newContents = <<<'PHP'
<?php
declare(strict_types=1);

// This file is auto-generated. Do not edit directly!

return %s;

PHP;
        $newContents = sprintf($newContents, var_export($result, true));

        $filename = self::getTranslationsFilePath();
        $oldContents = file_exists($filename) ? file_get_contents($filename) : '';

        if ($newContents !== $oldContents) {
            $this->output->note('There were translation changes.');
            // Save to the translations file.
            file_put_contents($filename, $newContents);
        }

        $this->output->text(
            "Finished downloading translations from OneSky in " . Profiling::stopwatchToHuman($stopwatchEvent->stop())
        );
    }

    public static function getTranslationsFilePath(): string
    {
        return storage_path('app/translations.php');
    }
}
