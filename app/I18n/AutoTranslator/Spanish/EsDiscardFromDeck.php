<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator\Spanish;

/**
 * Discarding cards from the deck
 */
class EsDiscardFromDeck
{
    public static function callback(array $matches): string
    {
        $youOrOpponent = $matches[1];
        $amount = $matches[2];
        $text = "$amount carta" . ($amount == '1' ? '' : 's');
        if ($youOrOpponent === '自分') {
            $text = "descarta $text de la parte superior de tu Deck";
        } else {
            $text = "tu adversario descarta $text de la parte superior de su Deck";
        }
        return $text;
    }
}
