<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Etc;

use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\StorageAttributes;

class FilesystemsCopier
{
    public function __construct(private FilesystemAdapter $src, private FilesystemAdapter $dst)
    {
    }

    /**
     * Copy if the file haven't changed.
     */
    public function copyCached($srcPath, $dstPath): void
    {
        if ($this->src->directoryExists($srcPath)) {
            if (!$this->dst->exists($dstPath)) {
                $this->dst->makeDirectory($dstPath);
            }

            foreach ($this->src->listContents($srcPath) as $attributes) {
                /** @var StorageAttributes $attributes */
                $file = $attributes['path'];
                $basename = basename($file);
                $this->copyCached($file, "$dstPath/$basename");
            }

            return;
        }

        if (
            $this->dst->exists($dstPath) &&
            (
                $this->src->lastModified($srcPath) <= $this->dst->lastModified($dstPath) || // Source path not newer.
                $this->src->read($srcPath) === $this->dst->read($dstPath) // Same contents.
            )
        ) {
            return;
        }

        $this->dst->writeStream($dstPath, $this->src->readStream($srcPath));
    }
}
