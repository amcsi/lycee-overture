<?php
declare(strict_types=1);

use Rector\Config\RectorConfig;
use RectorLaravel\Set\LaravelLevelSetList;

return RectorConfig::configure()
    // register single rule
    ->withSets([
        LaravelLevelSetList::UP_TO_LARAVEL_110,
    ]);
