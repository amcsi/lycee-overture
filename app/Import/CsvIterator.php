<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Import;

use GuzzleHttp\Psr7\Utils;

/**
 * Returns an iterator the Lycee Overture CSV file imported from the Japanese website
 * with the lycee:download-csv command.
 */
class CsvIterator
{
    /**
     * @return iterable|string[]
     */
    public function getIterator()
    {
        $f = Utils::tryFopen(storage_path(ImportConstants::CSV_PATH), 'r');

        $expectedColumnCount = 22;

        $rows = [];

        while (($row = fgets($f)) !== false) {
            $rows[] = $row;
        }

        $rows = array_reverse($rows);
        // Explode by comma.
        $reader = (function () use ($rows, $expectedColumnCount) {
            foreach ($rows as $line) {
                $row = array_map(trim(...), explode(',', $line));
                if ($row[CsvColumns::ID] === 'LO-3558' && $row[CsvColumns::TYPE] === '向坂 海希') {
                    $row = array_merge(array_slice($row, 0, CsvColumns::TYPE), array_slice($row, CsvColumns::TYPE + 1));
                }
                $columnCount = count($row);
                if ($columnCount > $expectedColumnCount) {
                    throw new \RuntimeException("Column count went over $expectedColumnCount.");
                }
                yield $row;
            }
        })();

        return $reader;
    }
}
