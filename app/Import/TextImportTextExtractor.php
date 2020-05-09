<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Import;

use amcsi\LyceeOverture\I18n\JapaneseCharacterCounter;
use amcsi\LyceeOverture\I18n\Locale;

/**
 * Extracts text to columns in the import
 */
class TextImportTextExtractor
{
    public function toDatabaseRows(iterable $reader): \Traversable
    {
        foreach ($reader as $csvRow) {
            $id = $csvRow[CsvColumns::ID];
            if (!preg_match('/^[A-Z]{2}-\d{4}$/', $id)) {
                // Skip alternative variants of cards.
                continue;
            }
            $dbRow = [];
            $dbRow['card_id'] = $id;
            $dbRow['locale'] = Locale::JAPANESE;
            $dbRow['name'] = $csvRow[CsvColumns::NAME];
            $rawBasicAbilities = $csvRow[CsvColumns::BASIC_ABILITIES];
            if ($rawBasicAbilities && $rawBasicAbilities[0] !== '[') {
                // Fix basic abilities that were forgotten to be wrapped in brackets from the official website.
                $rawBasicAbilities = "[$rawBasicAbilities]";
            }
            $dbRow['basic_abilities'] = $rawBasicAbilities;
            $dbRow['ability_name'] = $csvRow[CsvColumns::ABILITY_NAME];
            foreach (CsvValueInterpreter::getAbilityPartsFromAbility($csvRow[CsvColumns::ABILITY]) as $dbKey => $val) {
                $dbRow[$dbKey] = $val;
            }
            $dbRow['kanji_count'] = JapaneseCharacterCounter::countJapaneseCharactersForDbRow($dbRow);
            $dbRow['character_type'] = $csvRow[CsvColumns::CHARACTER_TYPE];
            yield $dbRow;
        }
    }
}
