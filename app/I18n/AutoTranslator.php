<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n;

use amcsi\LyceeOverture\I18n\AutoTranslator\AbilityGainsOrOther;
use amcsi\LyceeOverture\I18n\AutoTranslator\DiscardFromDeck;
use amcsi\LyceeOverture\I18n\AutoTranslator\DrawCards;
use amcsi\LyceeOverture\I18n\AutoTranslator\FullWidthCharacters;
use amcsi\LyceeOverture\I18n\AutoTranslator\StatChanges;
use amcsi\LyceeOverture\I18n\AutoTranslator\Target;
use amcsi\LyceeOverture\I18n\AutoTranslator\WhenAppears;
use amcsi\LyceeOverture\I18n\AutoTranslator\WhenSupporting;

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
        $bracketCounts = self::countBrackets($japaneseText);

        $autoTranslated = $japaneseText;

        $autoTranslated = preg_replace('/。$/', '.', $autoTranslated);
        $autoTranslated = preg_replace_callback('/。|、|・/', function ($match) {
            return self::$punctuationMap[$match[0]] . ' ';
        }, $autoTranslated);
        $autoTranslated = FullWidthCharacters::translateFullWidthCharacters($autoTranslated);

        $autoTranslated = AbilityGainsOrOther::autoTranslate($autoTranslated);

        // "... get $statChanges."
        $autoTranslated = StatChanges::autoTranslate($autoTranslated);

        $autoTranslated = str_replace('自ターン中に使用する', 'use during your turn', $autoTranslated);
        $autoTranslated = str_replace('相手ターン中に使用する', 'use during your opponent\'s turn', $autoTranslated);
        $autoTranslated = str_replace('バトル中に使用できない', 'do not use during battle', $autoTranslated);
        $autoTranslated = str_replace('このキャラのバトル中に使用する', 'use during battle involving this character', $autoTranslated);
        $autoTranslated = str_replace('バトル中に使用する', 'use during battle', $autoTranslated);
        $autoTranslated = str_replace('このターン中', 'during this turn', $autoTranslated);
        $autoTranslated = WhenSupporting::autoTranslate($autoTranslated);
        $autoTranslated = WhenAppears::autoTranslate($autoTranslated);
        $autoTranslated = DrawCards::autoTranslate($autoTranslated);
        $autoTranslated = DiscardFromDeck::autoTranslate($autoTranslated);
        $autoTranslated = Target::autoTranslate($autoTranslated);

        // Condense multiple spaces into one; trim.
        $autoTranslated = trim(preg_replace('/ {2,}/', ' ', $autoTranslated));
        // Fix capitalization.
        $autoTranslated = preg_replace_callback('/(^[a-z]|(?:\.\s*)[a-z])/', function ($matches) {
            return strtoupper($matches[1]);
        }, $autoTranslated);

        if (self::countBrackets($autoTranslated) !== $bracketCounts) {
            throw new \LogicException("Bracket count mismatch.\nOriginal: $japaneseText\nTranslated: $autoTranslated");
        }

        return $autoTranslated;
    }

    /**
     * @param string $japaneseText
     * @return array
     */
    private static function countBrackets(string $japaneseText): array
    {
        $charsToLookAt = [ord('{') => 0, ord('}') => 0];
        $charCounts = array_intersect_key(count_chars($japaneseText, 1), $charsToLookAt);
        return $charCounts;
    }
}
