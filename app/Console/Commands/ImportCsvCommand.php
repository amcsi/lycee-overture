<?php

namespace amcsi\LyceeOverture\Console\Commands;

use amcsi\LyceeOverture\Debug\Profiling;
use amcsi\LyceeOverture\Import\CsvDownloader;
use Illuminate\Console\Command;
use Symfony\Component\Stopwatch\Stopwatch;
use function GuzzleHttp\Psr7\copy_to_stream;
use function GuzzleHttp\Psr7\stream_for;
use function GuzzleHttp\Psr7\try_fopen;

class ImportCsvCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lycee:import-csv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports the CSV file with the Lycee cards from the official website.';
    /**
     * @var CsvDownloader
     */
    private $csvDownloader;

    public function __construct(CsvDownloader $csvDownloader)
    {
        parent::__construct();
        $this->csvDownloader = $csvDownloader;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $stopwatch = new Stopwatch();
        $output = $this->output;
        $output->text('Importing CSV from Lycee website...');
        $stopwatch->start('import-csv');
        $response = $this->csvDownloader->import();

        $cacheFile = storage_path('lycee.csv');
        $cacheFileStream = stream_for(try_fopen($cacheFile, 'w+'));
        // Copy contents of download to CSV file.
        copy_to_stream($response->getBody(), $cacheFileStream);

        $importCsvStopwatchEvent = $stopwatch->stop('import-csv');
        $output->text('Done importing CSV. ' . Profiling::stopwatchToHuman($importCsvStopwatchEvent));
    }
}
