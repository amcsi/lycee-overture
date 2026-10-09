<?php
declare(strict_types=1);

namespace Tests\Unit\Image\Local;

use amcsi\LyceeOverture\Image\Local\LocalImageFiles;
use PHPUnit\Framework\TestCase;

class LocalImageFilesTest extends TestCase
{
    private string $dir;

    protected function setUp(): void
    {
        $this->dir = sys_get_temp_dir() . '/files-test-' . uniqid();
        mkdir("$this->dir/full/cards", 0777, true);
        touch("$this->dir/full/cards/LO-0001.webp");
        touch("$this->dir/full/cards/LO-0001.jpg");
        file_put_contents("$this->dir/../secret-" . basename($this->dir), 'secret');
    }

    protected function tearDown(): void
    {
        exec('rm -rf ' . escapeshellarg($this->dir));
        @unlink("$this->dir/../secret-" . basename($this->dir));
    }

    public function testExtensionlessPathPrefersTheFirstCandidate(): void
    {
        self::assertSame("$this->dir/full/cards/LO-0001.webp", LocalImageFiles::resolve($this->dir, 'full/cards/LO-0001'));
    }

    public function testExactPath(): void
    {
        self::assertSame("$this->dir/full/cards/LO-0001.jpg", LocalImageFiles::resolve($this->dir, 'full/cards/LO-0001.jpg'));
    }

    public function testMissing(): void
    {
        self::assertNull(LocalImageFiles::resolve($this->dir, 'full/cards/LO-9999'));
    }

    public function testRefusesToLeaveTheDirectory(): void
    {
        self::assertNull(LocalImageFiles::resolve($this->dir, '../secret-' . basename($this->dir)));
    }
}
