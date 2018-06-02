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

        // "This character gains X."
        $autoTranslated = preg_replace_callback(
            '/この(\[[^\]+]\])?キャラに((?:(?:AP|DP|SP|DMG)[+-]\d(?:, )?)+)する./u',
            function ($matches) use ($autoTranslated) {
                $something = '';
                if ($matches[1]) {
                    $something = ' ' . trim($matches[1]);
                }
                $replacement = "this$something character gets $matches[2].";
                $position = strpos($autoTranslated, $matches[0]);
                if ($position === 0) {
                    // Beginning of the sentence. Capitalize first letter.
                    $replacement = ucfirst($replacement);
                } else {
                    if ($autoTranslated[$position - 1] === '.') {
                        // Period before replacement. Capitalize first letter.
                        $replacement = ucfirst($replacement);
                    }
                    // Add space before word.
                    $replacement = " $replacement";
                }
                return $replacement;
            },
            $autoTranslated
        );

        return $autoTranslated;
    }
}
