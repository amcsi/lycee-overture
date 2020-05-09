<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

class CapitalizationFixer
{
    public static function fixCapitalization(string $text): string
    {
        return preg_replace_callback(
            '/((?:\.\s+|^\s*|\\[)[a-z](?!ttp))/m',
            ['self', 'uppercaseCallback'],
            $text
        );
    }

    private static function uppercaseCallback(array $matches): string
    {
        return strtoupper($matches[1]);
    }
}
