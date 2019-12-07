<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Etc\FilesystemsCopier;

class TransformResult
{
    private $getResourceResult;
    private $filenameResult;

    /**
     * @param string $filenameResult The new filename of the file.
     * @param callable $getResourceResult Callback to lazily get the resource result.
     */
    public function __construct(string $filenameResult, callable $getResourceResult)
    {
        $this->getResourceResult = $getResourceResult;
        $this->filenameResult = $filenameResult;
    }

    public function getFilenameResult(): string
    {
        return $this->filenameResult;
    }

    public function getResourceResult()
    {
        return ($this->getResourceResult)();
    }
}
