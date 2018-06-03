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
        return preg_replace_callback('/(\d)枚ドローする/', ['self', 'callback'], $text);
    }

    private static function callback(array $matches): string
    {
        $amount = $matches[1];
        $s = $amount != '1' ? 's' : '';
        return "draw $matches[1] card$s";
    }
}
