<?php

namespace amcsi\LyceeOverture\Console\Commands;

use amcsi\LyceeOverture\Card;
use amcsi\LyceeOverture\Import\BasicImportCsvFilterer;
use amcsi\LyceeOverture\Import\ImportConstants;
use Illuminate\Console\Command;
use League\Csv\Reader;

class ImportBasicCardsCommand extends Command
{
    protected $signature = 'lycee:import-basic-cards';
    protected $description = 'Imports the basic data of cards (excluding text)';
    private $basicImportCsvFilterer;

    public function __construct(BasicImportCsvFilterer $basicImportCsvFilterer)
    {
        parent::__construct();
        $this->basicImportCsvFilterer = $basicImportCsvFilterer;
    }

    public function handle()
    {
        $this->output->writeln('Starting import of basic card data.');
        $reader = Reader::createFromPath(storage_path(ImportConstants::CSV_PATH));
        $toInsert = iterator_to_array($this->basicImportCsvFilterer->toDatabaseRows($reader));
        $insertedCount = Card::getQuery()->insertIgnore($toInsert);
        $updatedCount = Card::getQuery()->upsert($toInsert) / 2;
        $this->output->writeln(sprintf(
            'Finished import of basic card data. Inserted: %s, Updated: %s',
            $insertedCount,
            $updatedCount
        ));
    }
}
