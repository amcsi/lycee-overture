<?php
declare(strict_types=1);

namespace Tests\Feature\Console;

use amcsi\LyceeOverture\Image\Local\ImageSources;
use amcsi\LyceeOverture\Image\Local\ImageVariantGenerator;
use Illuminate\Foundation\Testing\TestCase;
use Psr\Log\NullLogger;
use Tests\CreatesApplication;

class GenerateImagesCommandTest extends TestCase
{
    use CreatesApplication;

    private string $dir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dir = sys_get_temp_dir() . '/generate-test-' . uniqid();
        foreach (['cards', 'static'] as $directory) {
            mkdir("$this->dir/$directory", 0777, true);
        }
        $image = imagecreatetruecolor(10, 14);
        imagepng($image, "$this->dir/cards/LO-0001.png");
        imagepng($image, "$this->dir/static/a.png");
        imagepng($image, "$this->dir/static/b.png");
        file_put_contents("$this->dir/static/broken.png", 'not an image');

        $this->app->instance(ImageSources::class, new ImageSources("$this->dir/cards", "$this->dir/static"));
        $this->app->instance(
            ImageVariantGenerator::class,
            new ImageVariantGenerator("$this->dir/out", new NullLogger(), ['webp']),
        );
    }

    protected function tearDown(): void
    {
        exec('rm -rf ' . escapeshellarg($this->dir));
        parent::tearDown();
    }

    public function testStaticOnlySkipsCardsAndReportsBrokenImagesWithoutStopping(): void
    {
        $this->artisan('lycee:generate-images', ['--static' => true, '--workers' => 1])
            ->expectsOutputToContain('broken')
            ->assertFailed();

        self::assertFileExists("$this->dir/out/full/a.jpg");
        self::assertFileExists("$this->dir/out/full/b.jpg");
        self::assertFileDoesNotExist("$this->dir/out/full/cards/LO-0001.jpg");
    }

    public function testShardOnlyProcessesItsShare(): void
    {
        unlink("$this->dir/static/broken.png");

        $this->artisan('lycee:generate-images', ['--shard' => '1/2'])->assertSuccessful();

        // Sorted paths: a, b, cards/LO-0001; shard 1 of 2 gets the second one.
        self::assertFileDoesNotExist("$this->dir/out/full/a.jpg");
        self::assertFileExists("$this->dir/out/full/b.jpg");
        self::assertFileDoesNotExist("$this->dir/out/full/cards/LO-0001.jpg");
    }
}
