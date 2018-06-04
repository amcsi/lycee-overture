<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Import;

use amcsi\LyceeOverture\I18n\Locale;
use amcsi\LyceeOverture\Import\CsvValueInterpreter\MarkupConverter;

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
            $dbRow['basic_abilities'] = MarkupConverter::convert($csvRow[CsvColumns::BASIC_ABILITIES]);
            $dbRow['ability_name'] = $csvRow[CsvColumns::ABILITY_NAME];
            foreach (CsvValueInterpreter::getAbilityPartsFromAbility($csvRow[CsvColumns::ABILITY]) as $dbKey => $val) {
                $dbRow[$dbKey] = $val;
            }
            yield $dbRow;
        }
    }
}
