<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Import;

use League\Csv\Reader;

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
        $contents = file_get_contents(storage_path(ImportConstants::CSV_PATH));
        $contents = preg_replace("#(<br />)?\s*\r\n#", "<br />", $contents);
        /** @var Reader $reader */
        $reader = Reader::createFromString($contents);
        $reader = array_reverse(iterator_to_array($reader)); // Reverse to ensure that newer cards have newer dates.

        return $reader;
    }
}
