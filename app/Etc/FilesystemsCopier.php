<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Etc;

use amcsi\LyceeOverture\Etc\FilesystemsCopier\TransformResult;
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

        if ($this->src === $this->dst) {
            $this->src->copy($srcPath, $dstPath);
        } else {
            throw new \LogicException('Copying across filesystems is not supported yet.');
        }
    }

    /**
     * Performs a transform of the source file to the destination one, but only if the source is newer.
     */
    public function transformCached($srcPath, $dstDir, callable $callable)
    {
        if ($this->src->getMetadata($srcPath)['type'] === 'dir') {
            if (!$this->dst->exists($dstDir)) {
                $this->dst->makeDirectory($dstDir);
            }

            foreach ($this->src->listContents($srcPath) as $fileinfo) {
                $file = $fileinfo['path'];
                $this->transformCached($file, "$dstDir/$fileinfo[basename]", $callable);
            }

            return;
        }

        $result = $callable($srcPath);
        assert($result instanceof TransformResult);

        $dstPath = dirname($dstDir) . '/' . $result->getFilenameResult();

        if ($this->dst->exists($dstPath) && $this->src->lastModified($srcPath) <= $this->dst->lastModified($dstPath)) {
            return;
        }

        $resourceResult = $result->getResourceResult();
        if (!$resourceResult) {
            // Ignore.
            return;
        }
        $this->dst->putStream($dstPath, $resourceResult);

    }
}
