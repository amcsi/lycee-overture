<?php
declare(strict_types=1);

namespace Tests\Unit\Image\Local;

use amcsi\LyceeOverture\Image\Local\ImageVariantGenerator;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use RuntimeException;

class ImageVariantGeneratorTest extends TestCase
{
    private string $dir;
    private string $source;

    protected function setUp(): void
    {
        $this->dir = sys_get_temp_dir() . '/variant-test-' . uniqid();
        mkdir("$this->dir/src", 0777, true);
        $this->source = "$this->dir/src/card.png";
        imagepng(self::createCard(200, 300), $this->source);
    }

    protected function tearDown(): void
    {
        exec('rm -rf ' . escapeshellarg($this->dir));
    }

    /** A red card with a transparent top-left corner, like the real card scans. */
    private static function createCard(int $width, int $height): \GdImage
    {
        $image = imagecreatetruecolor($width, $height);
        imagealphablending($image, false);
        imagesavealpha($image, true);
        imagefill($image, 0, 0, imagecolorallocatealpha($image, 200, 30, 30, 0));
        // Big enough that JPEG compression can't smear the corner pixel into the red.
        imagefilledrectangle($image, 0, 0, 9, 9, imagecolorallocatealpha($image, 0, 0, 0, 127));
        return $image;
    }

    private function generator(array $autoEncoders = ['webp']): ImageVariantGenerator
    {
        return new ImageVariantGenerator("$this->dir/out", new NullLogger(), $autoEncoders);
    }

    public function testWritesEveryVariant(): void
    {
        self::assertSame(5, $this->generator()->generate('cards/LO-0001', $this->source));

        $out = "$this->dir/out";
        foreach (['full', 'h150', 'h300', 'h520'] as $directory) {
            self::assertFileExists("$out/$directory/cards/LO-0001.webp");
        }
        self::assertFileExists("$out/full/cards/LO-0001.jpg");
        self::assertFileExists("$out/w281/cards/LO-0001.jpg");
        self::assertFileDoesNotExist("$out/h150/cards/LO-0001.jpg");
        self::assertFileDoesNotExist("$out/w281/cards/LO-0001.webp");
    }

    public function testResizesWithoutEnlarging(): void
    {
        $this->generator()->generate('cards/LO-0001', $this->source);

        $out = "$this->dir/out";
        self::assertSame([100, 150], array_slice(getimagesize("$out/h150/cards/LO-0001.webp"), 0, 2));
        self::assertSame([200, 300], array_slice(getimagesize("$out/h520/cards/LO-0001.webp"), 0, 2));
        self::assertSame([200, 300], array_slice(getimagesize("$out/w281/cards/LO-0001.jpg"), 0, 2));
        self::assertSame([200, 300], array_slice(getimagesize("$out/full/cards/LO-0001.jpg"), 0, 2));
    }

    public function testFlattensJpegsOntoWhiteButKeepsTransparencyElsewhere(): void
    {
        $this->generator()->generate('cards/LO-0001', $this->source);

        $jpeg = imagecreatefromjpeg("$this->dir/out/full/cards/LO-0001.jpg");
        $rgb = imagecolorsforindex($jpeg, imagecolorat($jpeg, 0, 0));
        self::assertGreaterThan(230, min($rgb['red'], $rgb['green'], $rgb['blue']));

        $webp = imagecreatefromwebp("$this->dir/out/full/cards/LO-0001.webp");
        self::assertSame(127, imagecolorsforindex($webp, imagecolorat($webp, 0, 0))['alpha']);
    }

    public function testKeepsOnlyTheSmallestAutoCandidate(): void
    {
        $this->generator(['webp', 'jpg'])->generate('cards/LO-0001', $this->source);

        $candidates = array_filter(
            ['webp', 'jpg'],
            fn($ext) => is_file("$this->dir/out/h150/cards/LO-0001.$ext"),
        );
        self::assertCount(1, $candidates);
    }

    public function testSkipsUpToDateVariants(): void
    {
        $this->generator()->generate('cards/LO-0001', $this->source);

        self::assertSame(0, $this->generator()->generate('cards/LO-0001', $this->source));
    }

    public function testRegeneratesWhenTheSourceIsNewer(): void
    {
        $this->generator()->generate('cards/LO-0001', $this->source);
        touch($this->source, time() + 10);

        self::assertSame(5, $this->generator()->generate('cards/LO-0001', $this->source));
    }

    public function testForceRegeneratesUpToDateVariants(): void
    {
        $this->generator()->generate('cards/LO-0001', $this->source);

        self::assertSame(5, $this->generator()->generate('cards/LO-0001', $this->source, force: true));
    }

    public function testRemovesAStaleAutoFileOfAnotherType(): void
    {
        mkdir("$this->dir/out/h150/cards", 0777, true);
        file_put_contents("$this->dir/out/h150/cards/LO-0001.avif", 'stale');

        $this->generator()->generate('cards/LO-0001', $this->source, force: true);

        self::assertFileDoesNotExist("$this->dir/out/h150/cards/LO-0001.avif");
        self::assertFileExists("$this->dir/out/h150/cards/LO-0001.webp");
    }

    public function testAcceptsPaletteAndJpegSources(): void
    {
        $palette = imagecreate(30, 40);
        imagecolorallocate($palette, 10, 200, 10);
        imagepng($palette, "$this->dir/src/palette.png");
        imagejpeg(self::createCard(30, 40), "$this->dir/src/photo.jpg");

        self::assertSame(5, $this->generator()->generate('rules/palette', "$this->dir/src/palette.png"));
        self::assertSame(5, $this->generator()->generate('rules/photo', "$this->dir/src/photo.jpg"));
    }

    public function testThrowsForAnUnreadableSource(): void
    {
        file_put_contents("$this->dir/src/broken.png", 'not an image');

        $this->expectException(RuntimeException::class);
        $this->generator()->generate('cards/broken', "$this->dir/src/broken.png");
    }
}
