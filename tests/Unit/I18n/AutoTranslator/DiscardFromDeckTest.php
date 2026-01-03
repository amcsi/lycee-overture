<?php
declare(strict_types=1);

namespace Tests\Unit\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\DiscardFromDeck;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class DiscardFromDeckTest extends TestCase
{
    #[DataProvider('provideAutoTranslate')]
    public function testAutoTranslate($expected, $input)
    {
        self::assertSame($expected, DiscardFromDeck::autoTranslate($input));
    }

    public static function provideAutoTranslate(): array
    {
        return [
            [
                'your opponent discards 2 cards from the top of their deck.',
                '相手のデッキを2枚破棄する.',
            ],
            [
                'discard 1 card from the top of your deck.',
                '自分のデッキを1枚破棄する.',
            ],
        ];
    }
}
