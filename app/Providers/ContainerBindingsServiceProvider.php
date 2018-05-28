<?php

namespace amcsi\LyceeOverture\Providers;

use amcsi\LyceeOverture\Import\CsvDownloader;
use Illuminate\Config\Repository;
use Illuminate\Support\ServiceProvider;

/**
 * Container bindings
 */
class ContainerBindingsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        /** @var Repository $config */
        $config = $this->app->get('config');
        $this->app->when(CsvDownloader::class)
            ->needs('$config')
            ->give($config->get('import'));
    }
}
