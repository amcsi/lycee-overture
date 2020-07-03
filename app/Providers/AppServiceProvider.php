<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Providers;

use amcsi\LyceeOverture\Console\Commands\DownloadTranslations;
use amcsi\LyceeOverture\Http\ConfigureTrustedProxies;
use amcsi\LyceeOverture\I18n\JpnForPhp\TransliteratorFactory;
use amcsi\LyceeOverture\I18n\NameTranslator\KanjiTranslator;
use amcsi\LyceeOverture\I18n\NameTranslator\ManualNameTranslator;
use amcsi\LyceeOverture\I18n\OneSkyClient;
use amcsi\LyceeOverture\I18n\SetTranslator\SetTranslator;
use amcsi\LyceeOverture\I18n\TranslatorApi\YahooKanjiTranslator;
use amcsi\LyceeOverture\I18n\TranslatorApi\YahooRawKanjiTranslator;
use amcsi\LyceeOverture\I18n\TranslatorInterface;
use amcsi\LyceeOverture\Import\CsvDownloader;
use amcsi\LyceeOverture\Import\ImageDownloader;
use amcsi\LyceeOverture\Import\ImportConstants;
use amcsi\LyceeOverture\Import\Set\SetAutoCreator;
use amcsi\LyceeOverture\Set;
use Illuminate\Cache\CacheManager;
use Illuminate\Cache\Repository;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Database\Connection;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\ServiceProvider;
use JpnForPhp\Transliterator\Transliterator;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;

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
            /** @var Connection $connection */
            $connection = \DB::connection();
            $connection->flushQueryLog();
            $connection->enableQueryLog();
        }

        ConfigureTrustedProxies::configure();

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

        $app->when(OneSkyClient::class)
            ->needs('$oneSkyConfig')
            ->give($config->get('onesky'));

        $translationsFilePath = DownloadTranslations::getTranslationsFilePath();
        $app->when(ManualNameTranslator::class)
            ->needs('$translations')
            ->give(
                function () use ($translationsFilePath) {
                    return file_exists($translationsFilePath) ? include $translationsFilePath : [];
                }
            );

        $app->when(YahooRawKanjiTranslator::class)->needs('$apiKey')->give(env('YAHOO_TRANSLATOR_API_KEY'));

        $app->when(KanjiTranslator::class)->needs(TranslatorInterface::class)->give(
            function () use ($app) {
                return new YahooKanjiTranslator(
                    $app->make(YahooRawKanjiTranslator::class),
                    $app->make(CacheManager::class)->driver('yahooTranslations')
                );
            }
        );

        $app->singleton(Transliterator::class, \Closure::fromCallable([TransliteratorFactory::class, 'getInstance']));

        $app->when(SetAutoCreator::class)->needs('$sets')->give(
            function () use ($app) {
                return $app->get(Set::class)->all();
            }
        );

        $app->when(SetTranslator::class)->needs('$setTranslations')->give(
            function () use ($app) {
                return $app->get(Translator::class)->get('sets');
            }
        );
    }
}
