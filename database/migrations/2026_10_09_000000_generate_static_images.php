<?php
declare(strict_types=1);

use amcsi\LyceeOverture\Console\Commands\GenerateImagesCommand;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

/**
 * Generates the few static image variants on deploy. Card variants are left to the import that runs in the
 * background on every start, so the site doesn't wait for them.
 */
return new class extends Migration {
    public function up(): void
    {
        Artisan::call(GenerateImagesCommand::COMMAND, ['--static' => true, '--workers' => 1]);
    }

    public function down(): void
    {
    }
};
