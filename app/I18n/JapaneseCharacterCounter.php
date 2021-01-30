<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n;

use amcsi\LyceeOverture\CardTranslation;
use amcsi\LyceeOverture\Console\Commands\AutoTranslateCommand;

/**
 * Counts Hiragana, Katakana and Kanji characters in text.
 */
class JapaneseCharacterCounter
{
    public const KANJI_REGEX_RANGE = '\x{4E00}-\x{9FBF}';
    public const HIRAGANA_REGEX_RANGE = '\x{3040}-\x{309F}';
    public const KATAKANA_REGEX_RANGE = '\x{30A0}-\x{30FF}';
    public const JAPANESE_LETTERS_RANGES = self::KANJI_REGEX_RANGE . self::HIRAGANA_REGEX_RANGE . self::KATAKANA_REGEX_RANGE;

    public static function countJapaneseCharacters($string): int
    {
        $japaneseLettersRanges = self::JAPANESE_LETTERS_RANGES;
        return mb_strlen(preg_replace("/[^$japaneseLettersRanges]/u", '', $string));
    }

    /**
     * @param CardTranslation|string[] $dbRow
     */
    public static function countJapaneseCharactersForDbRow(\amcsi\LyceeOverture\CardTranslation|array $dbRow): int
    {
        $stringToCountJapaneseCharactersFor = '';
        foreach (AutoTranslateCommand::AUTO_TRANSLATE_FIELDS as $column) {
            $stringToCountJapaneseCharactersFor .= $dbRow[$column];
        }
        return self::countJapaneseCharacters($stringToCountJapaneseCharactersFor);
    }
}
