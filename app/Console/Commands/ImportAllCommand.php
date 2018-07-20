<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Console\Commands;

use Illuminate\Console\Command;

class ImportAllCommand extends Command
{
    protected $signature = 'lycee:import-all';
    protected $description = 'Does importing of the CSV, its data, and auto translations.';

    public function handle()
    {
        $this->call(DownloadCsvCommand::COMMAND);
        $this->call(ImportBasicCardsCommand::COMMAND);
        $this->call(ImportTextsCommand::COMMAND);
        $this->call(AutoTranslateCommand::COMMAND);
    }
}
