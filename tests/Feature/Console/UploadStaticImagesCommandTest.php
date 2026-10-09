<?php
declare(strict_types=1);

namespace Tests\Feature\Console;

use amcsi\LyceeOverture\CloudinaryUploader;
use Illuminate\Foundation\Testing\TestCase;
use Tests\CreatesApplication;

class UploadStaticImagesCommandTest extends TestCase
{
    use CreatesApplication;

    public function testUploadsEachFileOfTheFolderUnderItsExtensionlessPath(): void
    {
        $uploaded = [];
        $this->app->instance(CloudinaryUploader::class, new class ($uploaded) extends CloudinaryUploader {
            public function __construct(private array &$uploaded)
            {
            }

            public function upload(string $filename, string $publicId, array $params = []): void
            {
                $this->uploaded[$publicId] = $filename;
            }
        });

        $this->artisan('lycee:upload-static-images', ['folder' => 'rules'])->assertSuccessful();

        self::assertCount(27, $uploaded);
        self::assertSame(resource_path('image-sources/rules/index_2_img1.png'), $uploaded['rules/index_2_img1']);
        self::assertSame(resource_path('image-sources/rules/index_4_number3.jpg'), $uploaded['rules/index_4_number3']);
    }

    public function testFailsForAMissingFolder(): void
    {
        $this->artisan('lycee:upload-static-images', ['folder' => 'does-not-exist'])->assertFailed();
    }
}
