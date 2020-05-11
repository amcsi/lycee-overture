<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\Spanish\EsDiscardFromDeck;
use amcsi\LyceeOverture\I18n\Locale;

/**
 * Discarding cards from the deck
 */
class DiscardFromDeck
{
    const REGEX = '/(自分|相手)のデッキを(\d)枚破棄する/u';

    public static function autoTranslate(string $text, string $locale = null): string
    {
        if ($locale === Locale::SPANISH) {
            return preg_replace_callback(self::REGEX, [EsDiscardFromDeck::class, 'callback'], $text);
        }
        return preg_replace_callback(self::REGEX, ['self', 'callback'], $text);
    }

    private static function callback(array $matches): string
    {
        $youOrOpponent = $matches[1];
        $amount = $matches[2];
        $text = "$amount card" . ($amount == '1' ? '' : 's');
        if ($youOrOpponent === '自分') {
            $text = "discard $text from the top of your deck";
        } else {
            $text = "your opponent discards $text from the top of their deck";
        }
        return $text;
    }
}
