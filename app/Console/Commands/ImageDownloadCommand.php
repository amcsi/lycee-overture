<?php

namespace amcsi\LyceeOverture\Console\Commands;

use amcsi\LyceeOverture\Debug\Profiling;
use amcsi\LyceeOverture\Import\ImageDownloader;
use Illuminate\Console\Command;
use Symfony\Component\Stopwatch\Stopwatch;

class ImageDownloadCommand extends Command
{
    protected $signature = 'lycee:download-images';
    protected $description = 'Downloads the images of the cards.';
    private $imageDownloader;

    public function __construct(ImageDownloader $imageDownloader)
    {
        parent::__construct();
        $this->imageDownloader = $imageDownloader;
    }

    public function handle()
    {
        $stopwatch = new Stopwatch();
        $downloadImagesEvent = $stopwatch->start('download-images');
        $this->output->text('Started downloading images...');
        $this->imageDownloader->downloadImages($this->output);
        $downloadImagesEvent->stop();
        $this->output->text("Finished downloading images in " . Profiling::stopwatchToHuman($downloadImagesEvent));
    }
}
