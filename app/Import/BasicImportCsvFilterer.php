<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Import;

/**
 * Converts a CSV reader to database rows for importing cards.
 */
class BasicImportCsvFilterer
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
            $dbRow['id'] = $id;
            $dbRow['type'] = CsvValueInterpreter::getType($csvRow);
            $dbRow['ex'] = (int) $csvRow[CsvColumns::EX];
            $dbRow['rarity'] = $csvRow[CsvColumns::RARITY];
            foreach (CsvValueInterpreter::getElements($csvRow) as $key => $value) {
                $dbRow[$key] = $value;
            }
            foreach (CsvValueInterpreter::getCosts($csvRow) as $key => $value) {
                $dbRow[$key] = $value;
            }
            $dbRow['ap'] = (int) $csvRow[CsvColumns::AP];
            $dbRow['dp'] = (int) $csvRow[CsvColumns::DP];
            $dbRow['sp'] = (int) $csvRow[CsvColumns::SP];
            $dbRow['dmg'] = (int) $csvRow[CsvColumns::DMG];
            $dbRow['ability_type'] = CsvValueInterpreter::getAbilityType($csvRow);
            yield $dbRow;
        }
    }
}
