<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

/**
 * Auto-translate drawing cards.
 */
class DrawCards
{
    public static function autoTranslate(string $text): string
    {
        return preg_replace_callback('/(相手は)?(\d)枚ドロー(する|できる)/', self::callback(...), $text);
    }

    public static function callback(array $matches): string
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
