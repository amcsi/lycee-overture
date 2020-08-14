<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Console\Commands;

use amcsi\LyceeOverture\I18n\Tools\NameCommaFixer;
use amcsi\LyceeOverture\I18n\Tools\TextFixer;
use Illuminate\Console\Command;

class FixNameCommasCommand extends Command
{
    protected $signature = 'lycee:fix-name-commas {--dry-run}';

    protected $description = 'Removed commas from within names';

    public function handle()
    {
        $result = TextFixer::applyTextFixOnAll([NameCommaFixer::class, 'fix'], $this->option('dry-run'));
        /** @noinspection ForgottenDebugOutputInspection */
        dd($result);
    }
}
