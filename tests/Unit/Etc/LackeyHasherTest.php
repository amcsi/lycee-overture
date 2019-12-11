<?php
declare(strict_types=1);

namespace Tests\Unit\Etc;

use amcsi\LyceeOverture\Etc\LackeyHasher;
use PHPUnit\Framework\TestCase;

class LackeyHasherTest extends TestCase
{
    /**
     * @dataProvider provideFilesToHash
     */
    public function testHashesCorrectly($expected, $filename): void
    {
        self::assertSame($expected, LackeyHasher::hashFile($filename));
    }

    public function provideFilesToHash()
    {
        return [
            [350485, __DIR__ . '/350485.txt'],
            [372237, __DIR__ . '/372237.txt'],
        ];
    }
}
