<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Import\Set;

/**
 * Extracts the set's name and version from its full name.
 */
class SetNameExtracter
{
    public static function extract(string $fullName): array
    {
        return array_replace(['', ''], explode(' ', $fullName, 2));
    }
}
