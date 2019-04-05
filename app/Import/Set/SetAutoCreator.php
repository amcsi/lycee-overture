<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Import\Set;

use amcsi\LyceeOverture\Set;

/**
 * Gets or creates the set ID by the full Japanese name of a set plus the version number.
 */
class SetAutoCreator
{
    private $sets;

    public function __construct($sets)
    {
        $this->sets = collect($sets)->keyBy(function (Set $set) {
            return sprintf('%s %s', $set->name_ja, $set->version);
        });
    }

    public function getOrCreateSetIdByJaFullName($jaFullName): ?int
    {
        $return = null;
        if (isset($this->sets[$jaFullName])) {
            $return = $this->sets[$jaFullName]->id;
        }

        return $return;
    }
}
