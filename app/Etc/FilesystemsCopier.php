<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Etc;

use Illuminate\Filesystem\FilesystemAdapter;

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
        if ($this->src->getMetadata($srcPath)['type'] === 'dir') {
            if (!$this->dst->exists($dstPath)) {
                $this->dst->makeDirectory($dstPath);
            }

            foreach ($this->src->listContents($srcPath) as $fileinfo) {
                $file = $fileinfo['path'];
                $this->copyCached($file, "$dstPath/$fileinfo[basename]");
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

        $this->dst->putStream($dstPath, $this->src->readStream($srcPath));
    }
}
