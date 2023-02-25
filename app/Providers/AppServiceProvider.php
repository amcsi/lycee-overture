<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Providers;

use amcsi\LyceeOverture\Console\Commands\DownloadTranslations;
use amcsi\LyceeOverture\I18n\JpnForPhp\TransliteratorFactory;
use amcsi\LyceeOverture\I18n\NameTranslator\CachedDeeplTranslator;
use amcsi\LyceeOverture\I18n\NameTranslator\DeeplCacheStore;
use amcsi\LyceeOverture\I18n\NameTranslator\KanjiTranslator;
use amcsi\LyceeOverture\I18n\NameTranslator\ManualNameTranslator;
use amcsi\LyceeOverture\I18n\NameTranslator\NameTranslator;
use amcsi\LyceeOverture\I18n\NullTranslator;
use amcsi\LyceeOverture\I18n\OneSkyClient;
use amcsi\LyceeOverture\I18n\SetTranslator\SetTranslator;
use amcsi\LyceeOverture\I18n\TranslatorApi\YahooRawKanjiTranslator;
use amcsi\LyceeOverture\I18n\TranslatorInterface;
use amcsi\LyceeOverture\Import\CsvDownloader;
use amcsi\LyceeOverture\Import\ImageDownloader;
use amcsi\LyceeOverture\Import\ImportConstants;
use amcsi\LyceeOverture\Import\Set\SetAutoCreator;
use amcsi\LyceeOverture\Set;
use Carbon\CarbonImmutable;
use DeepL\Translator;
use Illuminate\Cache\Repository;
use Illuminate\Database\Connection;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\ServiceProvider;
use JpnForPhp\Transliterator\Transliterator;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\Local\LocalFilesystemAdapter;

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

        if (self::$booted) {
            return;
        }
        Builder::macro('myUpsert', require __DIR__ . '/../../app/Database/upsert.php');
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
        Date::use(CarbonImmutable::class);

        /** @var Repository $config */
        $config = $this->app->get('config');
        $app = $this->app;

        $app->when(CsvDownloader::class)
            ->needs('$config')
            ->give($config->get('import'));

        $app->when(ImageDownloader::class)
            ->needs(FilesystemOperator::class)
            ->give(function () {
                $adapter = new LocalFilesystemAdapter(storage_path(ImportConstants::ORIGINAL_CARD_IMAGES_PATH));
                return new Filesystem($adapter);
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
                try {
                    return $app->get(CachedDeeplTranslator::class);
                } catch (\Throwable $e) {
                    report($e);
                    return $app->get(NullTranslator::class);
                }
            }
        );

        $app->singleton(Transliterator::class, \Closure::fromCallable([TransliteratorFactory::class, 'getInstance']));

        $app->when(SetAutoCreator::class)->needs('$sets')->give(
            function () use ($app) {
                return $app->get(Set::class)->all();
            }
        );

        $app->when(SetTranslator::class)->needs('$setTranslations')->give(
            function () {
                return config('lycee.sets');
            }
        );

        $app->singleton('japaneseFaker', fn() => \Faker\Factory::create('ja_JP'));

        $app->when(NameTranslator::class)->needs('$namesToTranslateAsWhole')->give(
            config('lycee.names_to_translate_as_whole')
        );

        $app->singleton(Translator::class, fn () => new Translator(config('services.deepl.authKey')));
        $app->singleton(DeeplCacheStore::class);
    }
}
