<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

/**
 * Discarding cards from the deck
 */
class DiscardFromDeck
{
    public static function autoTranslate(string $text): string
    {
        return preg_replace_callback('/(自分|相手)のデッキを(\d)枚破棄する/', ['self', 'callback'], $text);
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
