<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Console\Commands;

use amcsi\LyceeOverture\I18n\Tools\QuoteFixer;
use Illuminate\Console\Command;

class FixQuotesCommand extends Command
{
    protected $signature = 'lycee:fix-quotes {--dry-run}';

    protected $description = 'Fixes quotes from ascii quotation marks to special quotation marks';

    public function handle()
    {
        $quoteFixerResult = QuoteFixer::fixQuotesOnAll($this->option('dry-run'));
        /** @noinspection ForgottenDebugOutputInspection */
        dd($quoteFixerResult);
    }
}
