<?php
declare(strict_types=1);

namespace Tests\Unit\Image;

use amcsi\LyceeOverture\Image\CloudinaryImageUrlBuilder;
use amcsi\LyceeOverture\Image\ImageFormat;
use amcsi\LyceeOverture\Image\ImageUrlTemplates;
use PHPUnit\Framework\TestCase;

class ImageUrlTemplatesTest extends TestCase
{
    public function testHasATemplatePerFormatKeyedByCaseName(): void
    {
        $templates = ImageUrlTemplates::build(new CloudinaryImageUrlBuilder('drkxqkguu'));

        self::assertSame(array_column(ImageFormat::cases(), 'name'), array_keys($templates));
        self::assertSame(
            'https://res.cloudinary.com/drkxqkguu/image/upload/q_auto,f_auto,h_150/{path}.jpg',
            $templates['Height150'],
        );
    }
}
