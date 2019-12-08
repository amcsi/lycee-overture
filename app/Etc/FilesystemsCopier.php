<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Etc;

use Illuminate\Filesystem\FilesystemAdapter;

class FilesystemsCopier
{
    private $src;
    private $dst;

    public function __construct(FilesystemAdapter $src, FilesystemAdapter $dst)
    {
        $this->src = $src;
        $this->dst = $dst;
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

        if ($this->dst->exists($dstPath) && $this->src->lastModified($srcPath) <= $this->dst->lastModified($dstPath)) {
            return;
        }

        $this->dst->putStream($dstPath, $this->src->readStream($srcPath));
    }
}
