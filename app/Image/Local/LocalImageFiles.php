<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Image\Local;

/**
 * Resolves a requested variant path to a file the same way nginx's try_files does for /storage/images/.
 */
final class LocalImageFiles
{
    public static function resolve(string $outputDir, string $path): ?string
    {
        if (in_array('..', explode('/', $path), true)) {
            return null;
        }
        $suffixes = ['', ...array_map(fn($ext) => ".$ext", ImageVariantGenerator::AUTO_EXTENSIONS)];
        foreach ($suffixes as $suffix) {
            $file = "$outputDir/$path$suffix";
            if (is_file($file)) {
                return $file;
            }
        }
        return null;
    }
}
