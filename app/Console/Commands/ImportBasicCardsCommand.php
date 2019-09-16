<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Console\Commands;

use amcsi\LyceeOverture\Card;
use amcsi\LyceeOverture\Import\BasicImportCsvFilterer;
use amcsi\LyceeOverture\Import\ImportConstants;
use Illuminate\Console\Command;
use League\Csv\Reader;

class ImportBasicCardsCommand extends Command
{
    const COMMAND = 'lycee:import-basic-cards';

    protected $signature = self::COMMAND .
    ' {--reset-dates : Resets all cards\' creation the dates, making newer cards have later dates}';
    protected $description = 'Imports the basic data of cards (excluding text)';

    public function handle()
    {
        $this->output->writeln('Starting import of basic card data.');
        /** @var Reader $reader */
        $reader = Reader::createFromPath(storage_path(ImportConstants::CSV_PATH));
        $reader = array_reverse(iterator_to_array($reader)); // Reverse to ensure that newer cards have newer dates.
        $toInsert = app(BasicImportCsvFilterer::class)->toDatabaseRows($reader, (bool) $this->option('reset-dates'));
        $insertedCount = Card::getQuery()->insertIgnore($toInsert);
        $updatedCount = Card::getQuery()->upsert($toInsert) / 2;
        $this->output->writeln(sprintf(
            'Finished import of basic card data. Inserted: %s, Updated: %s',
            $insertedCount,
            $updatedCount
        ));
    }
}
