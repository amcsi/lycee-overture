<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n;

use amcsi\LyceeOverture\Console\Commands\AutoTranslateCommand;

/**
 * Counts Hiragana, Katakana and Kanji characters in text.
 */
class JapaneseCharacterCounter
{
    public static function countJapaneseCharacters($string): int
    {
        return mb_strlen(preg_replace('/[^\x{4E00}-\x{9FBF}\x{3040}-\x{309F}\x{30A0}-\x{30FF}]/u', '', $string));
    }

    public static function countJapaneseCharactersForDbRow(array $dbRow): int
    {
        $stringToCountJapaneseCharactersFor = '';
        foreach (AutoTranslateCommand::AUTO_TRANSLATE_FIELDS as $column) {
            $stringToCountJapaneseCharactersFor .= $dbRow[$column];
        }
        return self::countJapaneseCharacters($stringToCountJapaneseCharactersFor);
    }
}
