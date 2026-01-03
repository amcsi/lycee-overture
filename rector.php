<?php
declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\PHPUnit\Set\PHPUnitSetList;
use RectorLaravel\Set\LaravelLevelSetList;

return RectorConfig::configure()
    // register single rule
    ->withSets([
        LaravelLevelSetList::UP_TO_LARAVEL_110,
        PHPUnitSetList::PHPUNIT_120,
        PHPUnitSetList::ANNOTATIONS_TO_ATTRIBUTES,
    ]);
