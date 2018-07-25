<?php
declare(strict_types=1);

namespace Tests\Unit\LyceeOverture\Card\Image;

use amcsi\LyceeOverture\Card\Image\ImagePathOriginalVariantDeterminer;
use PHPUnit\Framework\TestCase;

class ImagePathOriginalVariantDeterminerTest extends TestCase
{
    /**
     * @dataProvider provideOriginalVariant
     */
    public function testOriginalVariant(string $input)
    {
        self::assertTrue(ImagePathOriginalVariantDeterminer::isOriginalVariant($input));
    }

    public function provideOriginalVariant()
    {
        return [
            ['LO-0001.png'],
            ['LO-0001.jpeg'],
        ];
    }

    /**
     * @dataProvider provideNotOriginalVariant
     */
    public function testNotOriginalVariant(string $input)
    {
        self::assertFalse(ImagePathOriginalVariantDeterminer::isOriginalVariant($input));
    }

    public function provideNotOriginalVariant()
    {
        return [
            ['LO-0001-A.png'],
            ['lol'],
        ];
    }
}
