<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Image\Local;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Source files of the images to generate variants of, keyed by image path (e.g. "cards/LO-0001").
 */
class ImageSources
{
    public function __construct(private string $cardsDir, private string $staticDir)
    {
    }

    /** @return array<string, string> */
    public function cards(): array
    {
        $sources = [];
        foreach (glob("$this->cardsDir/*.png") ?: [] as $file) {
            $sources['cards/' . pathinfo($file, PATHINFO_FILENAME)] = $file;
        }
        ksort($sources);
        return $sources;
    }

    /** @return array<string, string> */
    public function statics(): array
    {
        if (!is_dir($this->staticDir)) {
            return [];
        }
        $sources = [];
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->staticDir, FilesystemIterator::SKIP_DOTS)
        );
        foreach ($files as $file) {
            if (!in_array(strtolower($file->getExtension()), ['png', 'jpg', 'jpeg'], true)) {
                continue;
            }
            $relativePath = substr($file->getPathname(), strlen($this->staticDir) + 1);
            $sources[preg_replace('/\.\w+$/', '', $relativePath)] = $file->getPathname();
        }
        ksort($sources);
        return $sources;
    }
}
