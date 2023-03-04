<?php
declare(strict_types=1);

namespace Tests\Unit\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\DrawCards;
use PHPUnit\Framework\TestCase;

class DrawCardsTest extends TestCase
{
    /**
     * @dataProvider provideAutoTranslate
     */
    public function testAutoTranslate(string $expected, string $input)
    {
        self::assertSame($expected, DrawCards::autoTranslate($input));
    }

    public static function provideAutoTranslate()
    {
        return [
            [
                'draw 2 cards',
                '2枚ドローする',
            ],
            [
                'you can draw 2 cards',
                '2枚ドローできる',
            ],
            [
                'your opponent draws 2 cards',
                '相手は2枚ドローする',
            ],
            [
                'your opponent can draw 2 cards',
                '相手は2枚ドローできる',
            ],
        ];
    }
}
