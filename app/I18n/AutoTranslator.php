<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n;

use amcsi\LyceeOverture\I18n\AutoTranslator\FullWidthCharacters;

/**
 * Auto-translates parts of Japanese text.
 */
class AutoTranslator
{
    private static $punctuationMap = [
        '。' => '.',
        '、' => ',',
        '・' => ',',
    ];

    public static function autoTranslate(string $japaneseText): string
    {
        $autoTranslated = $japaneseText;

        $autoTranslated = preg_replace('/。$/', '.', $autoTranslated);
        $autoTranslated = preg_replace_callback('/。|、|・/', function ($match) {
            return self::$punctuationMap[$match[0]] . ' ';
        }, $autoTranslated);
        $autoTranslated = FullWidthCharacters::translateFullWidthCharacters($autoTranslated);

        // "This character gets X."
        $autoTranslated = preg_replace_callback(
            '/この(\[[^\]+]\])?キャラに((?:(?:AP|DP|SP|DMG)[+-]\d(?:, )?)+)する./u',
            function ($matches) use ($autoTranslated) {
                return " this $matches[1] character gets $matches[2].";
            },
            $autoTranslated
        );

        // "This character gains X."
        $autoTranslated = preg_replace_callback(
            '/このキャラは(\[[^\]]+\])を得る\./u',
            function ($matches) use ($autoTranslated) {
                return " this character gains $matches[1].";
            },
            $autoTranslated
        );

        // Condense multiple spaces into one; trim.
        $autoTranslated = trim(preg_replace('/ {2,}/', ' ', $autoTranslated));
        // Fix capitalization.
        $autoTranslated = preg_replace_callback('/(^[a-z]|(?=\.\s*)[a-z])/', function ($matches) {
            return strtoupper($matches[1]);
        }, $autoTranslated);

        return $autoTranslated;
    }
}
