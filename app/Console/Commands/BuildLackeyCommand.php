<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Console\Commands;

use amcsi\LyceeOverture\Etc\FilesystemsCopier;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BuildLackeyCommand extends Command
{
    protected $signature = 'lycee:build-lackey';

    protected $description = 'Builds plugins for LackeyCCG';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $lackeyResourcesPath = __DIR__ . '/../../../resources/lackeyccg';

        $dstBasePath = __DIR__ . '/../../../storage/lackey';
        $dstPath = "$dstBasePath/lycee-lackeyccg-en-only-translated";

        $adapter = Storage::drive('localRoot');
        $copier = new FilesystemsCopier($adapter, $adapter);

        $copier->copyCached($lackeyResourcesPath, $dstPath);
    }
}
