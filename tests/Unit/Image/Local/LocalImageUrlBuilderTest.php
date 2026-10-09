<?php
declare(strict_types=1);

namespace Tests\Unit\Image\Local;

use amcsi\LyceeOverture\Image\ImageFormat;
use amcsi\LyceeOverture\Image\Local\LocalImageUrlBuilder;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class LocalImageUrlBuilderTest extends TestCase
{
    private LocalImageUrlBuilder $builder;

    protected function setUp(): void
    {
        $this->builder = new LocalImageUrlBuilder('https://lycee-tcg.eu/storage/images/');
    }

    public function testUrlIsExtensionless(): void
    {
        self::assertSame(
            'https://lycee-tcg.eu/storage/images/h150/cards/LO-0001A',
            $this->builder->url('cards/LO-0001A', ImageFormat::Height150),
        );
    }

    public function testJpegUrl(): void
    {
        self::assertSame(
            'https://lycee-tcg.eu/storage/images/full/cardback.jpg',
            $this->builder->jpegUrl('cardback', ImageFormat::Full),
        );
    }

    public function testJpegUrlRefusesVariantsWithoutJpegs(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->builder->jpegUrl('cards/LO-0001', ImageFormat::Height150);
    }

    public function testFolderUrlForLackey(): void
    {
        self::assertSame(
            'https://lycee-tcg.eu/storage/images/w281/cards/',
            $this->builder->folderUrl('cards', ImageFormat::Width281),
        );
    }
}
