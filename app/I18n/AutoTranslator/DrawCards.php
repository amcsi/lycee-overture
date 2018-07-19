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
        return preg_replace_callback('/(\d)枚ドロー(する|できる)/', ['self', 'callback'], $text);
    }

    private static function callback(array $matches): string
    {
        $amount = next($matches);
        $canOrDoSource = next($matches);
        $mandatory = true;
        if ($canOrDoSource === 'できる') {
            $mandatory = false;
        }
        $s = $amount != '1' ? 's' : '';
        $verb = $mandatory ? 'draw' : 'you can draw';
        return "$verb $matches[1] card$s";
    }
}
