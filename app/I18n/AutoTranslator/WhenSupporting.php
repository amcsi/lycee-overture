<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

/**
 * Auto-translation for text related to supporting.
 */
class WhenSupporting
{
    public static function autoTranslate(string $text): string
    {
        $text = str_replace('このキャラでサポートをしたとき', 'when this character supports another character', $text);
        $text = str_replace('このキャラにサポートをしたとき', 'when this character gets supported', $text);
        return $text;
    }
}