<?php
declare(strict_types=1);

namespace Tests\Import\Set;

use amcsi\LyceeOverture\Import\Set\SetNameExtracter;
use PHPUnit\Framework\TestCase;

class SetNameExtracterTest extends TestCase
{
    /**
     * @dataProvider provideExtract
     */
    public function testExtract($expected, $input)
    {
        self::assertSame($expected, SetNameExtracter::extract($input));
    }

    public static function provideExtract()
    {
        return [
            'Something and a version' => [
                ['something', '1.0'],
                'something 1.0',
            ],
            'Stuff after the version number' => [
                ['something', '1.0 other'],
                'something 1.0 other',
            ],
        ];
    }
}
