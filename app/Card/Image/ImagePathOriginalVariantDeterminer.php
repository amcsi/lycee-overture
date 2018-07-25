<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Card\Image;

/**
 * Determines whether the given filename (without directories) represents an image of the original variant.
 * E.g. LO-0001.png passes, but LO-0001-A.png does not.
 */
class ImagePathOriginalVariantDeterminer
{
    public static function isOriginalVariant(string $filename): bool
    {
        return (bool) preg_match('/^LO-\d{4}\./', $filename);
    }
}
