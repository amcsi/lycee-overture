<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Import;

use amcsi\LyceeOverture\Card;
use amcsi\LyceeOverture\Import\Set\SetAutoCreator;
use Carbon\CarbonImmutable;

/**
 * Converts a CSV reader to database rows for importing cards.
 */
class BasicImportCsvFilterer
{
    private $dateFormat;

    public function __construct(private SetAutoCreator $setAutoCreator)
    {
        $this->dateFormat = (new Card())->getDateFormat();
    }

    public function toDatabaseRows(iterable $reader): array
    {
        $ret = [];
        foreach ($reader as $csvRow) {
            $id = $csvRow[CsvColumns::ID];
            if (!preg_match('/^[A-Z]{2}-\d{4}$/', $id)) {
                // Skip alternative variants of cards.
                continue;
            }
            try {
                $ret[] = $this->assembleDbRow($csvRow);
            } catch (\Throwable $exception) {
                \Log::warning("Could not import card `$id`:\n" . $exception);
            }
        }
        return $ret;
    }

    private function assembleDbRow(array $csvRow): array
    {
        $dbRow = [];
        $dbRow['id'] = $csvRow[CsvColumns::ID];

        // E.g. Fate/Grand Order 2.0
        $cardSetText = $csvRow[CsvColumns::CARD_SET];

        $dbRow['set_id'] = $this->setAutoCreator->getOrCreateSetIdByJaFullName($cardSetText);

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
        $now = (new CarbonImmutable())->format($this->dateFormat);
        $dbRow['created_at'] = $now;
        $dbRow['updated_at'] = $now;
        return $dbRow;
    }
}
