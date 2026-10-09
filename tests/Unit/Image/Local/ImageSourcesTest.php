<?php
declare(strict_types=1);

namespace Tests\Unit\Image\Local;

use amcsi\LyceeOverture\Image\Local\ImageSources;
use PHPUnit\Framework\TestCase;

class ImageSourcesTest extends TestCase
{
    private string $dir;

    protected function setUp(): void
    {
        $this->dir = sys_get_temp_dir() . '/sources-test-' . uniqid();
        mkdir("$this->dir/cards", 0777, true);
        mkdir("$this->dir/static/rules", 0777, true);
        touch("$this->dir/cards/LO-0002.png");
        touch("$this->dir/cards/LO-0001A.png");
        touch("$this->dir/static/cardback.png");
        touch("$this->dir/static/rules/index_3_img.jpg");
        touch("$this->dir/static/rules/notes.txt");
    }

    protected function tearDown(): void
    {
        exec('rm -rf ' . escapeshellarg($this->dir));
    }

    public function testCards(): void
    {
        $sources = new ImageSources("$this->dir/cards", "$this->dir/static");

        self::assertSame(
            ['cards/LO-0001A' => "$this->dir/cards/LO-0001A.png", 'cards/LO-0002' => "$this->dir/cards/LO-0002.png"],
            $sources->cards(),
        );
    }

    public function testStaticsAreFoundRecursivelyWithoutExtensions(): void
    {
        $sources = new ImageSources("$this->dir/cards", "$this->dir/static");

        self::assertSame(
            [
                'cardback' => "$this->dir/static/cardback.png",
                'rules/index_3_img' => "$this->dir/static/rules/index_3_img.jpg",
            ],
            $sources->statics(),
        );
    }

    public function testMissingDirectoriesHaveNoSources(): void
    {
        $sources = new ImageSources("$this->dir/nope", "$this->dir/nope");

        self::assertSame([], $sources->cards());
        self::assertSame([], $sources->statics());
    }
}
