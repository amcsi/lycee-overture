<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Import;

use function GuzzleHttp\Psr7\try_fopen;

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
        $f = try_fopen(storage_path(ImportConstants::CSV_PATH), 'r');

        $expectedColumnCount = 22;
        $expectedCommaCount = $expectedColumnCount - 1;

        $rows = [];

        while (($row = fgets($f)) !== false) {
            while (($commaCount = substr_count($row, ',')) < $expectedCommaCount) {
                $additionalRow = fgets($f);
                if (!$additionalRow) {
                    throw new \RuntimeException("Could not get any more rows.");
                }
                $row .= $additionalRow;
            }
            if ($commaCount > $expectedCommaCount) {
                throw new \RuntimeException("Comma count went over $expectedCommaCount.");
            }
            $rows[] = $row;
        }

        $rows = array_reverse($rows);
        // Explode by comma.
        $reader = (function () use ($rows) {
            foreach ($rows as $row) {
                yield array_map(trim(...), explode(',', $row));
            }
        })();

        return $reader;
    }
}
