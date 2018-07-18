<?php
declare(strict_types=1);

namespace Tests\Unit\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\IfCardsInHand;
use PHPUnit\Framework\TestCase;

class IfCardsInHandTest extends TestCase
{
    /**
     * @dataProvider provideAutoTranslate
     * @param $expected
     * @param $input
     */
    public static function testAutoTranslate(string $expected, string $input): void
    {
        self::assertSame($expected, IfCardsInHand::autoTranslate($input));
    }

    public function provideAutoTranslate(): array
    {
        return [
            [
                'if you have 1 or less cards in your hand',
                '自分の手札が1枚以下の場合',
            ],
            [
                'if you have 2 or less cards in your hand',
                '自分の手札が2枚以下の場合',
            ],
            [
                'if you have 2 or more cards in your hand',
                '自分の手札が2枚以上の場合',
            ],
            [
                'if you have 0 cards in your hand',
                '自分の手札が0枚の場合',
            ],
            [
                'if you have 1 card in your hand',
                '自分の手札が1枚の場合',
            ],
        ];
    }
}
