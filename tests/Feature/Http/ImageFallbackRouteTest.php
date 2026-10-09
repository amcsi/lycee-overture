<?php
declare(strict_types=1);

namespace Tests\Feature\Http;

use Illuminate\Foundation\Testing\TestCase;
use Tests\CreatesApplication;

class ImageFallbackRouteTest extends TestCase
{
    use CreatesApplication;

    private string $dir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dir = sys_get_temp_dir() . '/route-test-' . uniqid();
        mkdir("$this->dir/h150/cards", 0777, true);
        imagewebp(imagecreatetruecolor(4, 4), "$this->dir/h150/cards/LO-0001.webp");
        config(['images.output_path' => $this->dir]);
    }

    protected function tearDown(): void
    {
        exec('rm -rf ' . escapeshellarg($this->dir));
        parent::tearDown();
    }

    public function testServesTheBestFileForAnExtensionlessPath(): void
    {
        $response = $this->get('/storage/images/h150/cards/LO-0001');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'image/webp');
        self::assertStringContainsString('max-age=2592000', $response->headers->get('Cache-Control'));
        // Cloudflare doesn't cache responses that set cookies.
        $response->assertHeaderMissing('Set-Cookie');
    }

    public function testMissingImageIs404(): void
    {
        $this->get('/storage/images/h150/cards/LO-9999')->assertNotFound();
    }

    public function testPathTraversalIs404(): void
    {
        $this->get('/storage/images/..%2F..%2F.env')->assertNotFound();
    }
}
