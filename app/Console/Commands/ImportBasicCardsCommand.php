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

    protected $signature = self::COMMAND;
    protected $description = 'Imports the basic data of cards (excluding text)';

    public function handle()
    {
        $this->output->writeln('Starting import of basic card data.');
        $reader = Reader::createFromPath(storage_path(ImportConstants::CSV_PATH));
        $toInsert = iterator_to_array(app(BasicImportCsvFilterer::class)->toDatabaseRows($reader));
        $insertedCount = Card::getQuery()->insertIgnore($toInsert);
        $updatedCount = Card::getQuery()->upsert($toInsert) / 2;
        $this->output->writeln(sprintf(
            'Finished import of basic card data. Inserted: %s, Updated: %s',
            $insertedCount,
            $updatedCount
        ));
    }
}
