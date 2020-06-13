<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

/**
 * Translates full-width English characters.
 *
 * https://en.wikipedia.org/wiki/Halfwidth_and_fullwidth_forms
 */
class FullWidthCharacters
{
    /**
     * Does not transform quotes.
     */
    public static function translateFullWidthCharacters(string $input): string
    {
        $autoTranslated = $input;
        $autoTranslated = preg_replace_callback('/[\x{FF01}-\x{FF5D}]/u',
            function ($match) {
                $ascii = ord($match[0][2]);
                return chr($ascii - 96);
            },
            $autoTranslated);
        return $autoTranslated;
    }

    public static function transformQuotes(string $input): string
    {
        return str_replace(['「', '」'], ['“', '”'], $input);
    }

}
