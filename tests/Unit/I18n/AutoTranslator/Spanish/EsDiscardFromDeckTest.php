<?php
declare(strict_types=1);

namespace Tests\Unit\I18n\AutoTranslator\Spanish;

use amcsi\LyceeOverture\I18n\AutoTranslator\DiscardFromDeck;
use amcsi\LyceeOverture\I18n\Locale;
use PHPUnit\Framework\TestCase;

class EsDiscardFromDeckTest extends TestCase
{
    /**
     * @dataProvider provideAutoTranslate
     */
    public function testAutoTranslate($expected, $input)
    {
        self::assertSame($expected, DiscardFromDeck::autoTranslate($input, Locale::SPANISH));
    }

    public function provideAutoTranslate(): array
    {
        return [
            [
                'tu adversario descarta 2 cartas de la parte superior de su Deck.',
                '相手のデッキを2枚破棄する.',
            ],
            [
                'descarta 1 carta de la parte superior de tu Deck.',
                '自分のデッキを1枚破棄する.',
            ],
        ];
    }
}
