<?php
declare(strict_types=1);

namespace Tests\Tools;

use amcsi\LyceeOverture\Import\Set\SetAutoCreator;
use amcsi\LyceeOverture\Models\Set;
use Illuminate\Support\Collection;

class SetAutoCreatorStubber
{
    /**
     * @param Set|null $setModel Optional Set model override for mocking DB operations.
     * @return SetAutoCreator
     */
    public static function createInstanceWithSets(Set $setModel = null): SetAutoCreator
    {
        $set = new Set();
        $set->id = 1;
        $set->name_ja = 'オーガスト';
        $set->version = '1.0';
        $sets[] = $set;
        $set = new Set();
        $set->id = 2;
        $set->name_ja = 'Fate/Grand Order';
        $set->version = '2.0';
        $sets[] = $set;
        $sets = new Collection($sets);

        return new SetAutoCreator($sets, $setModel ?: new Set());
    }
}
