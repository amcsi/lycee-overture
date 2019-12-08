<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Console\Commands;

use amcsi\LyceeOverture\Card;
use amcsi\LyceeOverture\Card\CardTransformer;
use amcsi\LyceeOverture\CardTranslation;
use amcsi\LyceeOverture\Etc\FilesystemsCopier;
use amcsi\LyceeOverture\Etc\FilesystemsCopier\TransformResult;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;
use function GuzzleHttp\Psr7\try_fopen;

class BuildLackeyCommand extends Command
{
    protected $signature = 'lycee:build-lackey';

    protected $description = 'Builds plugins for LackeyCCG';

    private const THUMBNAIL_SIZE = [200, 269];

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $lackeyResourcesPath = __DIR__ . '/../../../resources/lackeyccg';

        $dstBasePath = __DIR__ . '/../../../storage/lackey';
        $dstPath = "$dstBasePath/lycee-lackeyccg-en-only-translated";

        $adapter = Storage::drive('localRoot');
        $copier = new FilesystemsCopier($adapter, $adapter);

        $copier->copyCached($lackeyResourcesPath, $dstPath);

        // Card data.
        $fileObject = new \SplTempFileObject();
        $writer = Writer::createFromFileObject($fileObject);
        $writer->setDelimiter("\t");
        $definitions = [
            'Name' => fn(Card $card) => $card->id,
            'Set' => fn() => 'cards',
            'ImageFile' => fn(Card $card) => $card->id,
            'Actual Name' => fn(Card $card) => $card->getTranslation()->name,
            'Ability Name' => fn(Card $card) => $card->getTranslation()->ability_name,
            'Character Type' => fn(Card $card) => $card->getTranslation()->character_type,
            'Comments' => fn(Card $card) => $card->getTranslation()->comments,
            'EX' => fn(Card $card) => $card->ex,
            'Element' => fn(Card $card) => CardTransformer::getElementMarkup($card),
            'Cost' => fn(Card $card) => CardTransformer::getCostMarkup($card),
            'DMG' => fn(Card $card) => $card->dmg,
            'AP' => fn(Card $card) => $card->ap,
            'DP' => fn(Card $card) => $card->dp,
            'SP' => fn(Card $card) => $card->sp,
            'Basic Abilities' => fn(Card $card) => $card->getTranslation()->basic_abilities,
            'Special Abilities' => function (Card $card) {
                $translation = $card->getTranslation();
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
        $writer->insertAll(
            Card::cursor()->map(fn(Card $card) => array_map(fn(callable $cb) => $cb($card), $definitions))
        );
        $fileObject->rewind();
        $f = try_fopen("$dstPath/sets/carddata.txt", 'wb');
        foreach ($fileObject as $row) {
            fwrite($f, $row);
        }
        fclose($f);

        /** @var Card $card */
        foreach (Card::query()->cursor() as $card) {
            /** @var CardTranslation $translation */
            $translation = $card->getTranslation();
            $writer->insertOne(
                [
                    $card->id,
                    'cards',
                    $card->id,
                    $translation->name,
                    $translation->ability_name,
                ]
            );
        }


        $copier->transformCached(
            storage_path('images/original-cards'),
            "$dstPath/sets/setimages/cards",
            function ($imageFile) {
                $newImageFile = pathinfo($imageFile, PATHINFO_FILENAME) . '.jpg';
                // Flysystem cuts off the leading slash.
                $imageFile = "/$imageFile";

                return new TransformResult(
                    $newImageFile, function () use ($imageFile) {
                    $imageSize = getimagesize($imageFile);
                    if (!$imageSize) {
                        // Not an image. Ignore.
                        return null;
                    }
                    $canvas = imagecreatetruecolor(...self::THUMBNAIL_SIZE);
                    $originalImage = imagecreatefrompng($imageFile);
                    imagecopyresized(
                        $canvas,
                        $originalImage,
                        0,
                        0,
                        0,
                        0,
                        self::THUMBNAIL_SIZE[0],
                        self::THUMBNAIL_SIZE[1],
                        $imageSize[0],
                        $imageSize[1]
                    );
                    $tempnam = tempnam('', sys_get_temp_dir());
                    imagejpeg($canvas, $tempnam);

                    $this->info("Copied over $imageFile");
                    return try_fopen($tempnam, 'r');
                }
                );
            }
        );


    }
}
