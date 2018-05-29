<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Import;

/**
 * Converts a CSV reader to database rows for importing cards.
 */
class BasicImportCsvFilterer
{
    public function toDatabaseRows(iterable $reader): iterable
    {
        foreach ($reader as $csvRow) {
            $id = $csvRow[CsvColumns::ID];
            if (!preg_match('/^[A-Z]{2}-\d{4}$/', $id)) {
                continue;
            }
            $dbRow = [];
            $dbRow['id'] = $id;
            $dbRow['type'] = CsvValueInterpreter::getType($csvRow);
            yield $dbRow;
        }
    }
}
