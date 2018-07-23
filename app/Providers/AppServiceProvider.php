<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Providers;

use amcsi\LyceeOverture\Import\CsvDownloader;
use amcsi\LyceeOverture\Import\ImageDownloader;
use amcsi\LyceeOverture\Import\ImportConstants;
use Illuminate\Cache\Repository;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\HttpFoundation\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Keep track of whether static macros have already been booted for this Laravel Swoole application.
     * @var bool
     */
    static private $booted = false;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('app.debug')) {
            // Flush before enabling query log.
            \DB::connection()->flushQueryLog();
            \DB::connection()->enableQueryLog();
        }

        // This is to make sure that even though HTTPS is proxies with the help of nginx-proxy (docker),
        // Laravel should still show treat the current request as HTTPS when calling getSchemeAndHttpHost().
        // TODO: find out Docker IP range to use instead of "*" for additional security.
        \Illuminate\Support\Facades\Request::setTrustedProxies(['*'], Request::HEADER_X_FORWARDED_ALL);

        if (self::$booted) {
            return;
        }
        Builder::macro('upsert', require __DIR__ . '/../../app/Database/upsert.php');
        Builder::macro('insertIgnore', require __DIR__ . '/../../app/Database/insertIgnore.php');

        self::$booted = true;
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
