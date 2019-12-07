<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Console\Commands;

use amcsi\LyceeOverture\Etc\FilesystemsCopier;
use amcsi\LyceeOverture\Etc\FilesystemsCopier\TransformResult;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
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

        $copier->transformCached(
            storage_path('images/original-cards'),
            "$dstPath/sets/setimage/cards",
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
