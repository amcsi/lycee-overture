<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Import;

use amcsi\LyceeOverture\Card;
use amcsi\LyceeOverture\Import\Set\SetAutoCreator;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use function iter\toArray;

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
        $rows = collect(toArray($reader));

        // Group cards together with the same ID (but possibly different variants).
        $groupedRows = $rows->groupBy(fn(array $row) => substr($row[CsvColumns::ID], 0, 7));

        $rows = $groupedRows->map(function (Collection $rows) {
            // Make sure the original variant is the first one.
            $rows = $rows->sortBy(0)->values();
            // We'll return the original variant eventually.
            $ret = $rows[0];

            if (strlen($ret[CsvColumns::ID]) !== 7) {
                throw new \RuntimeException(
                    'Expected first card ID of variant to not have a suffix. Actual: ' . $ret[CsvColumns::ID]
                );
            }

            // Put into the rarity column: the variants of the cards and their rarities.
            $ret[CsvColumns::RARITY] = [
                $rows->map(fn(array $row) => substr($row[CsvColumns::ID], 7))->join(','),
                $rows->map(fn(array $row) => $row[CsvColumns::RARITY])->join(','),
            ];

            return $ret;
        });

        $ret = [];
        foreach ($rows as $csvRow) {
            try {
                $ret[] = $this->assembleDbRow($csvRow);
            } catch (\Throwable $exception) {
                $id = $csvRow[CsvColumns::ID] ?? null;
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

        // Variants and rarities should be assembled from ImportBasicCardsCommand.
        $dbRow['variants'] = $csvRow[CsvColumns::RARITY][0];
        $dbRow['rarity'] = $csvRow[CsvColumns::RARITY][1];

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
