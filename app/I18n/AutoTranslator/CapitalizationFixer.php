<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

class CapitalizationFixer
{
    public static function fixCapitalization(string $text): string
    {
        // Periods and bullet points should make the next letter character be capitalized.
        $capitalizerCharacterRegex = '[\.•]';

        return preg_replace_callback(
            sprintf('/((?:%s\s+|^\s*|\[)[a-z](?!ttp))/mu', $capitalizerCharacterRegex),
            self::uppercaseCallback(...),
            $text
        );
    }

    private static function uppercaseCallback(array $matches): string
    {
        return strtoupper($matches[1]);
    }
}
