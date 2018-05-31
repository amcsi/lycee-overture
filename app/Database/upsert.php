<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Database;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;

/**
 * Performs an upsert. This has to be a closure to be able to rebind $this.
 */
return function (array $values): int {
    /** @var Builder $this */

    if (!$values) {
        return 0;
    }

    $query = $this->grammar->compileInsert($this, $values);

    $sets = array_map(function ($columnKey) {
        return "`$columnKey`=VALUES(`$columnKey`)";
    }, array_filter(array_keys($values[0]), function ($value) {
        // Filter out created_at for updates.
        return $value !== 'created_at' && $value !== 'id';
    }));

    $query .= ' ON DUPLICATE KEY UPDATE ' . implode(', ', $sets);

    $bindings = $this->cleanBindings(Arr::flatten($values, 1));
    return $this->connection->affectingStatement(
        $query,
        $bindings
    );
};
