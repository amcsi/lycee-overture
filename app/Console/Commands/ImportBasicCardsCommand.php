<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Console\Commands;

use amcsi\LyceeOverture\Models\Card;
use amcsi\LyceeOverture\Import\BasicImportCsvFilterer;
use amcsi\LyceeOverture\Import\CsvIterator;
use Illuminate\Console\Command;

class ImportBasicCardsCommand extends Command
{
    const COMMAND = 'lycee:import-basic-cards';

    protected $signature = self::COMMAND .
    ' {--reset-dates : Resets all cards\' creation the dates, making newer cards have later dates}';
    protected $description = 'Imports the basic data of cards (excluding text)';

    public function __construct(private CsvIterator $csvIterator)
    {
        parent::__construct();
    }

    public function handle()
    {
        $chunkizeOperation = fn($allData, $operationFn) => array_sum(
            array_map(
                fn($chunk) => $operationFn($chunk),
                array_chunk($allData, 1000)
            )
        );

        $this->output->writeln('Starting import of basic card data.');
        $toInsert = app(BasicImportCsvFilterer::class)->toDatabaseRows($this->csvIterator->getIterator());
        $insertedCount = $chunkizeOperation($toInsert, fn($chunk) => Card::getQuery()->insertIgnore($chunk));

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

        $updatedCount = $chunkizeOperation($toUpdate, fn($chunk) => Card::getQuery()->myUpsert($chunk)) / 2;
        $this->output->writeln(
            sprintf(
                'Finished import of basic card data. Inserted: %s, Updated: %s',
                $insertedCount,
                $updatedCount
            )
        );
    }
}
