<?php

namespace amcsi\LyceeOverture\Console\Commands;

use amcsi\LyceeOverture\CardTranslation;
use amcsi\LyceeOverture\Import\ImportConstants;
use amcsi\LyceeOverture\Import\TextImportTextExtractor;
use Illuminate\Console\Command;
use League\Csv\Reader;

class ImportTextsCommand extends Command
{
    protected $signature = 'lycee:import-texts';
    protected $description = 'Imports the japanese texts from the CSV';
    private $textImportTextExtractor;

    public function __construct(TextImportTextExtractor $textImportTextExtractor)
    {
        parent::__construct();
        $this->textImportTextExtractor = $textImportTextExtractor;
    }

    public function handle()
    {
        $this->output->writeln('Started import of japanese texts.');

        $reader = Reader::createFromPath(storage_path(ImportConstants::CSV_PATH));
        $rows = iterator_to_array($this->textImportTextExtractor->toDatabaseRows($reader));
        $insertedCount = CardTranslation::getQuery()->insertIgnore($rows);
        $updatedCount = CardTranslation::getQuery()->upsert($rows) / 2;

        $this->output->writeln(sprintf(
            'Finished import of japanese texts. Inserted: %s, Updated: %s',
            $insertedCount,
            $updatedCount
        ));
    }
}
