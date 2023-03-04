<?php
declare(strict_types=1);

namespace Tests\Unit\Import\CsvValueInterpreter;

use amcsi\LyceeOverture\Import\CsvValueInterpreter\MarkupConverter;
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

    public static function provideConvert(): array
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
                '[Order Step:[D1]]',
                '[オーダーステップ:[D1]]',
            ],
            'with tap' => [
                '[T][snow][moon][flower][space][sun][star]',
                '[T雪月花宙日無]',
            ],
            'cost ability type' => [
                '[Cost]',
                '[コスト]',
            ],
            'other characters in the markup' => [
                '[コストが２点以下のＡＦキャラ１体]',
                '[コストが２点以下のＡＦキャラ１体]',
            ],
        ];
    }

    /**
     * @dataProvider provideNormalizeBrackets
     */
    public function testNormalizeBrackets($expected, $input): void
    {
        self::assertSame($expected, MarkupConverter::normalizeBrackets($input));
    }

    public static function provideNormalizeBrackets(): array
    {
        return [
            [
                '',
                '',
            ],
            [
                '[雪]',
                '[雪]',
            ],
            [
                '[T][雪][月][花][宙][日][無]',
                '[T雪月花宙日無]',
            ],
            'full-width T' => [
                '[Ｔ][宙][宙]',
                '[Ｔ宙宙]',
            ],
        ];
    }
}
