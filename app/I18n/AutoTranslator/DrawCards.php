<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\Spanish\EsDrawCards;
use amcsi\LyceeOverture\I18n\Locale;

/**
 * Auto-translate drawing cards.
 */
class DrawCards
{
    private const REGEX = '/(相手は)?(\d)枚ドロー(する|できる)/u';

    public static function autoTranslate(string $text, string $locale = null): string
    {
        if ($locale === Locale::SPANISH) {
            return preg_replace_callback(self::REGEX, [EsDrawCards::class, 'callback'], $text);
        }
        return preg_replace_callback(self::REGEX, ['self', 'callback'], $text);
    }

    private static function callback(array $matches): string
    {
        $opponent = next($matches);
        $amount = next($matches);
        $canOrDoSource = next($matches);
        $mandatory = true;
        if ($canOrDoSource === 'できる') {
            $mandatory = false;
        }
        $who = $opponent ? 'your opponent ' : 'you ';
        if ($mandatory && !$opponent) {
            // Avoid the subject ("draw n cards")
            $who = '';
        }

        $verbS = $opponent ? 's' : '';
        $s = $amount != '1' ? 's' : '';
        $verb = $mandatory ? "draw$verbS" : 'can draw';
        return "$who$verb $amount card$s";
    }
}
