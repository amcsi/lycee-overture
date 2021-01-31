<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Console\Commands;

use amcsi\LyceeOverture\Debug\Profiling;
use amcsi\LyceeOverture\Import\CsvDownloader;
use amcsi\LyceeOverture\Import\ImportConstants;
use Cake\Chronos\Chronos;
use Illuminate\Console\Command;
use Symfony\Component\Stopwatch\Stopwatch;
use function GuzzleHttp\Psr7\copy_to_stream;
use function GuzzleHttp\Psr7\stream_for;
use function GuzzleHttp\Psr7\try_fopen;

class DownloadCsvCommand extends Command
{
    public const COMMAND = 'lycee:download-csv';

    protected $signature = self::COMMAND . ' {--f|force : Ignore cache}';
    protected $description = 'Imports the CSV file with the Lycee cards from the official website.';

    public function __construct(private CsvDownloader $csvDownloader)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $force = (bool) $this->option('force');
        $cacheFile = storage_path(ImportConstants::CSV_PATH);

        $output = $this->output;
        $output->text('Importing CSV from Lycee website...');
        $stopwatch = new Stopwatch();
        $importCsvStopwatchEvent = $stopwatch->start('import-csv');

        // Rely on cache if the cached file newer by a specific time interval.
        if (!$force && file_exists($cacheFile) && filemtime($cacheFile) > Chronos::now()->subWeek()->getTimestamp()) {
            $output->text('Done importing CSV (cache).');
            return;
        }

        $response = $this->csvDownloader->download();

        $cacheFileStream = stream_for(try_fopen($cacheFile, 'w+'));
        // Copy contents of download to CSV file.
        copy_to_stream($response->getBody(), $cacheFileStream);

        $importCsvStopwatchEvent->stop();
        $output->text('Done importing CSV. ' . Profiling::stopwatchToHuman($importCsvStopwatchEvent));
    }
}
