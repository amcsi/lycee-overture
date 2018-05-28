<?php

namespace amcsi\LyceeOverture\Providers;

use amcsi\LyceeOverture\Import\CsvDownloader;
use amcsi\LyceeOverture\Import\ImageDownloader;
use amcsi\LyceeOverture\Import\ImportConstants;
use Illuminate\Cache\Repository;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /** @var Repository $config */
        $config = $this->app->get('config');
        $app = $this->app;

        $app->when(CsvDownloader::class)
            ->needs('$config')
            ->give($config->get('import'));

        $app->when(ImageDownloader::class)
            ->needs(FilesystemInterface::class)
            ->give(function () {
                return new Filesystem(new Local(storage_path(ImportConstants::ORIGINAL_CARD_IMAGES_PATH)));
            });
    }
}
