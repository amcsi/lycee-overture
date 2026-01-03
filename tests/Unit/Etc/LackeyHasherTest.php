<?php
declare(strict_types=1);

namespace Tests\Unit\Etc;

use amcsi\LyceeOverture\Etc\LackeyHasher;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class LackeyHasherTest extends TestCase
{
    #[DataProvider('provideFilesToHash')]
    public function testHashesCorrectly($expected, $filename): void
    {
        self::assertSame($expected, LackeyHasher::hashFile($filename));
    }

    public static function provideFilesToHash()
    {
        return [
            [350485, __DIR__.'/350485.txt'],
            [372237, __DIR__.'/372237.txt'],
            [-20265, __DIR__.'/-20265.jpg'],
            [-8196, __DIR__.'/-8196.jpg'],
        ];
    }
}
