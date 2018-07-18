<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Import\CsvValueInterpreter;

use PHPUnit\Framework\TestCase;

class MarkupConverterTest extends TestCase
{
    /**
     * @dataProvider provideConvert
     */
    public function testConvert(string $expected, string $input): void
    {
        self::assertSame($expected, MarkupConverter::convert($input));
    }

    public function provideConvert(): array
    {
        return [
            [
                '',
                '',
            ],
            [
                '[snow][moon][flower][space][sun][star]',
                '[雪月花宙日無]',
            ],
            [
                '[OrderStep:[D1]]',
                '[オーダーステップ:[D1]]',
            ],
            'with tap' => [
                '[T][snow][moon][flower][space][sun][star]',
                '[T雪月花宙日無]',
            ],
        ];
    }
}
