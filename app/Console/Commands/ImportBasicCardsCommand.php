<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Console\Commands;

use amcsi\LyceeOverture\Card;
use amcsi\LyceeOverture\Import\BasicImportCsvFilterer;
use amcsi\LyceeOverture\Import\CsvIterator;
use Illuminate\Console\Command;

class ImportBasicCardsCommand extends Command
{
    const COMMAND = 'lycee:import-basic-cards';

    protected $signature = self::COMMAND .
    ' {--reset-dates : Resets all cards\' creation the dates, making newer cards have later dates}';
    protected $description = 'Imports the basic data of cards (excluding text)';

    private CsvIterator $csvIterator;

    public function __construct(CsvIterator $csvIterator)
    {
        parent::__construct();
        $this->csvIterator = $csvIterator;
    }

    public function handle()
    {
        $this->output->writeln('Starting import of basic card data.');
        $toInsert = app(BasicImportCsvFilterer::class)->toDatabaseRows($this->csvIterator->getIterator());
        $insertedCount = Card::getQuery()->insertIgnore($toInsert);

        $toUpdate = $this->option('reset-dates') ?
            $toInsert :
            array_map(
                function (array $row) {
                    // Do not re-apply creation and update dates if we are not resetting them.
                    unset($row['created_at'], $row['updated_at']);
                    return $row;
                },
                $toInsert
            );

        $updatedCount = Card::getQuery()->upsert($toUpdate) / 2;
        $this->output->writeln(
            sprintf(
                'Finished import of basic card data. Inserted: %s, Updated: %s',
                $insertedCount,
                $updatedCount
            )
        );
    }
}
