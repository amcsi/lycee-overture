<?php
declare(strict_types=1);

namespace Tests\Unit\Image;

use amcsi\LyceeOverture\Image\CloudinaryImageUrlBuilder;
use amcsi\LyceeOverture\Image\ImageFormat;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CloudinaryImageUrlBuilderTest extends TestCase
{
    /**
     * The expected URLs are the ones the app produced before this abstraction existed. They must stay byte-identical,
     * because Cloudinary treats any difference in the transformation string as a new derived image.
     */
    #[DataProvider('provideUrls')]
    public function testUrl(string $expected, string $path, ImageFormat $format): void
    {
        self::assertSame($expected, (new CloudinaryImageUrlBuilder('drkxqkguu'))->url($path, $format));
    }

    public static function provideUrls(): array
    {
        $base = 'https://res.cloudinary.com/drkxqkguu/image/upload';
        return [
            'full card' => ["$base/q_auto,f_auto/cards/LO-0001.jpg", 'cards/LO-0001', ImageFormat::Full],
            'cardback' => ["$base/q_auto,f_auto/cardback.jpg", 'cardback', ImageFormat::Full],
            'height 150' => ["$base/q_auto,f_auto,h_150/cards/LO-0001A.jpg", 'cards/LO-0001A', ImageFormat::Height150],
            'height 300' => ["$base/q_auto,f_auto,h_300/cards/LO-0001.jpg", 'cards/LO-0001', ImageFormat::Height300],
            'height 520' => ["$base/q_auto,f_auto,h_520/cards/LO-0001.jpg", 'cards/LO-0001', ImageFormat::Height520],
            'width 281' => ["$base/w_281/cards/LO-0001.jpg", 'cards/LO-0001', ImageFormat::Width281],
        ];
    }

    #[DataProvider('provideFolderUrls')]
    public function testFolderUrl(string $expected, string $folder, ImageFormat $format): void
    {
        self::assertSame($expected, (new CloudinaryImageUrlBuilder('drkxqkguu'))->folderUrl($folder, $format));
    }

    public static function provideFolderUrls(): array
    {
        $base = 'https://res.cloudinary.com/drkxqkguu/image/upload';
        return [
            'lackey medium' => ["$base/w_281/cards/", 'cards', ImageFormat::Width281],
            'lackey high quality' => ["$base/q_auto,f_auto/cards/", 'cards', ImageFormat::Full],
        ];
    }
}
