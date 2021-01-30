<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Console\Commands;

use amcsi\LyceeOverture\CardTranslation;
use amcsi\LyceeOverture\Import\CsvIterator;
use amcsi\LyceeOverture\Import\TextImportTextExtractor;
use Illuminate\Console\Command;

class ImportTextsCommand extends Command
{
    public const COMMAND = 'lycee:import-texts';

    protected $signature = self::COMMAND;
    protected $description = 'Imports the japanese texts from the CSV';

    public function __construct(
        private TextImportTextExtractor $textImportTextExtractor,
        private CsvIterator $csvIterator
    ) {
        parent::__construct();
    }

    public function handle()
    {
        $this->output->writeln('Started import of japanese texts.');

        $rows = iterator_to_array($this->textImportTextExtractor->toDatabaseRows($this->csvIterator->getIterator()));
        $insertedCount = CardTranslation::getQuery()->insertIgnore($rows);
        $updatedCount = CardTranslation::getQuery()->myUpsert($rows) / 2;

        $this->output->writeln(sprintf(
            'Finished import of japanese texts. Inserted: %s, Updated: %s',
            $insertedCount,
            $updatedCount
        ));
    }
}
