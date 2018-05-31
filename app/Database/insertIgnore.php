<?php
declare(strict_types=1);

/**
 * Performs an insert ignore. This has to be a closure to be able to rebind $this.
 */

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;

return function (array $values): int {
    /** @var Builder $this */

    if (!$values) {
        return 0;
    }

    $query = $this->grammar->compileInsert($this, $values);
    $query = preg_replace('/^insert/', 'insert ignore', $query);

    $bindings = $this->cleanBindings(Arr::flatten($values, 1));
    return $this->connection->affectingStatement(
        $query,
        $bindings
    );
};
