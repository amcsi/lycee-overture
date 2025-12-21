<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Console\Commands;

use amcsi\LyceeOverture\Models\Card;
use amcsi\LyceeOverture\Card\CardResource;
use amcsi\LyceeOverture\Debug\Profiling;
use amcsi\LyceeOverture\Etc\FilesystemsCopier;
use amcsi\LyceeOverture\Etc\Lackey\LackeyNameFormatter;
use amcsi\LyceeOverture\Etc\Lackey\LackeyStarterDecksAssember;
use amcsi\LyceeOverture\Etc\LackeyHasher;
use amcsi\LyceeOverture\Etc\LackeyVariant;
use GuzzleHttp\Psr7\Utils;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;
use Symfony\Component\Stopwatch\Stopwatch;

class BuildLackeyCommand extends Command
{
    public const COMMAND = 'lycee:build-lackey';

    protected $signature = self::COMMAND;

    protected $description = 'Builds plugins for LackeyCCG';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $stopwatchEvent = (new Stopwatch())->start('build-lackey');
        $this->output->text('Started building plugin for LackeyCCG.');

        $variants = [
            new LackeyVariant('lycee-overture-translated', 'w_281/cards', 'medium'),
            new LackeyVariant('lycee-overture-translated-highquality', 'q_auto/cards', 'high'),
        ];

        foreach ($variants as $variant) {
            $this->buildVariant($variant);
        }

        $this->output->text(
            'Finished building plugin for LackeyCCG in ' . Profiling::stopwatchToHuman($stopwatchEvent->stop())
        );
    }

    public function buildVariant(LackeyVariant $lackeyVariant)
    {
        $lackeyResourcesPath = __DIR__ . '/../../../resources/lackeyccg';

        $pluginFolderName = $lackeyVariant->getPluginFolderName();
        $dstPath = "lackey/$pluginFolderName";

        $adapter = Storage::drive('localRoot');

        $dstAdapter = Storage::createLocalDriver([
            'root' => sprintf("%s/%s", storage_path('app/public'), $dstPath),
            'url' => sprintf("%s/storage/%s", config('app.url'), $dstPath),
            'visibility' => 'public',
            'throw' => true,
        ]);

        $copier = new FilesystemsCopier($adapter, $dstAdapter);

        $copier->copyCached($lackeyResourcesPath, '/');

        // Card data.
        $tempnam = tempnam('', sys_get_temp_dir());
        $writer = Writer::createFromPath($tempnam);
        $writer->setDelimiter("\t");
        $definitions = [
            'Name' => fn(Card $card) => $card->id,
            'Set' => fn(Card $card) => LackeyNameFormatter::formatSet($card),
            'ImageFile' => fn(Card $card) => $card->id,
            'Actual Name' => fn(Card $card) => $card->getBestTranslation()->name,
            'Ability Name' => fn(Card $card) => $card->getBestTranslation()->ability_name,
            'Character Type' => fn(Card $card) => $card->getBestTranslation()->character_type,
            'Comments' => fn(Card $card) => str_replace("\n", ' ', $card->getBestTranslation()->comments),
            'EX' => fn(Card $card) => $card->ex,
            'Element' => fn(Card $card) => CardResource::getElementMarkup($card),
            'Cost' => fn(Card $card) => CardResource::getCostMarkup($card),
            'DMG' => fn(Card $card) => $card->dmg,
            'AP' => fn(Card $card) => $card->ap,
            'DP' => fn(Card $card) => $card->dp,
            'SP' => fn(Card $card) => $card->sp,
            'Basic Abilities' => fn(Card $card) => $card->getBestTranslation()->basic_abilities,
            'Special Abilities' => function (Card $card) {
                $translation = $card->getBestTranslation();
                $costs = explode("\n", $translation->ability_cost);
                $descriptions = explode("\n", $translation->ability_description);
                $ret = '';
                foreach ($costs as $key => $cost) {
                    if (!isset($descriptions[$key])) {
                        \Log::notice("Not the same amount of cost-description rows in {$card->id}");
                        return $ret;
                    }
                    $ret .= "$cost: $descriptions[$key]";
                }
                return $ret;
            },
        ];
        $writer->insertOne(array_keys($definitions));
        Card::with(['set', 'translations'])->chunk(1000, function ($cards) use ($writer, $definitions) {
            $writer->insertAll(
                $cards
                    // Exclude ones not fully translated.
                    ->filter(fn(Card $card) => !$card->getBestTranslation()->kanji_count)
                    ->map(fn(Card $card) => array_map(static fn(callable $cb) => $cb($card), $definitions))
            );
        });
        $dstAdapter->writeStream('sets/carddata.txt', Utils::tryFopen($tempnam, 'rb'));

        /*
         * Handle starter decks.
         */
        app(LackeyStarterDecksAssember::class)->handle($dstAdapter);

        // plugininfo.txt

        $updateListFirstRowToday = sprintf("%s\t%s", $pluginFolderName, date('m-d-y'));
        $lastUpdateListContents = $dstAdapter->exists('updatelist.txt') ?
            $dstAdapter->read('updatelist.txt') :
            $updateListFirstRowToday;

        $lastUpdateListFirstLine = strtok($lastUpdateListContents, "\n");
        // We try to use the last plugintext.txt's date if possible, in case the contents would end up the same.
        $dateText = explode("\t", $lastUpdateListFirstLine)[1];

        $newUpdateListContents = '';
        $newUpdateListContents .= "$pluginFolderName\t$dateText\n";

        $pluginInfoBasePath = "plugins/$pluginFolderName";
        $getPublicUrl = fn($path) => $dstAdapter->url($path);
        $versionFileUrl = $getPublicUrl('version.txt');

        $fileList = [
            'plugininfo.txt' => $getPublicUrl('plugininfo.txt'),
            'version.txt' => $versionFileUrl,
            'sets/carddata.txt' => $getPublicUrl('sets/carddata.txt'),
            'bot.jpg' => 'https://res.cloudinary.com/drkxqkguu/image/upload/q_auto,f_auto/cardback.jpg',
            'sets/setimages/general/cardback.jpg' => 'https://res.cloudinary.com/drkxqkguu/image/upload/q_auto,f_auto/cardback.jpg',
        ];
        foreach ($dstAdapter->listContents('decks') as $file) {
            $deckPath = $file['path'];
            $fileList[$deckPath] = $getPublicUrl($deckPath);
        }
        if (!$dstAdapter->exists('version.txt')) {
            // The version.txt file must exist so we can hash it.
            $dstAdapter->copy('version.dist.xml', "version.txt");
        }

        foreach ($fileList as $pluginFileRelativePath => $url) {
            $hash = self::hashFile($url);
            $newUpdateListContents .= "$pluginInfoBasePath/$pluginFileRelativePath\t$url\t$hash\n";
        }
        $newUpdateListContents .= "\n";
        $newUpdateListContents .= "CardGeneralURLs:\n";
        $newUpdateListContents .= sprintf(
            "https://res.cloudinary.com/%s/image/upload/%s/\n",
            config('cloudinary.defaults.cloud_name'),
            $lackeyVariant->getImagePrefix()
        );

        if ($lastUpdateListContents !== $newUpdateListContents) {
            // version.txt
            $versionFileContents = $adapter->read("$lackeyResourcesPath/version.dist.xml");
            $versionFileContents = str_replace(
                [':date:', ':versionUrl:', ':updateListUrl:', ':dateWithTime:'],
                [
                    date('Ymd'),
                    e($versionFileUrl),
                    e($getPublicUrl('updatelist.txt')),
                    // Try to ensure updating plugin can work multiple times a day by using the message as a cache buster.
                    e(date('Y-m-d H:i:s'))
                ],
                $versionFileContents
            );

            $dstAdapter->put('version.txt', $versionFileContents);

            // The contents did not end up the same. So let's replace the first line to show today's date instead.
            $newUpdateListContents = str_replace(
                $lastUpdateListFirstLine,
                $updateListFirstRowToday,
                $newUpdateListContents
            );
            // Update the version.txt hash to express that the version changed.
            $newUpdateListContents = preg_replace(
                sprintf("/\\b(?<=%s\t).*$/m", preg_quote($versionFileUrl, '/')),
                (string) self::hashFile($versionFileUrl),
                $newUpdateListContents,
                -1,
                $count
            );
            $dstAdapter->put('updatelist.txt', $newUpdateListContents);
        }
    }

    private static function hashFile(string $file): int
    {
        $appUrl = config('app.url');
        $appLocalUrl = config('app.localUrl');
        // Replace remote URL with local version.
        $hashUrl = str_replace($appUrl, $appLocalUrl, $file);
        return LackeyHasher::hashFile($hashUrl);
    }
}
