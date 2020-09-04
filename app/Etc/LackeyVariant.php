<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Etc;

class LackeyVariant
{
    private string $pluginFolderName;
    private string $imagePrefix;
    private string $quality;

    public function __construct(string $pluginFolderName, string $imagePrefix, string $quality)
    {
        $this->pluginFolderName = $pluginFolderName;
        $this->imagePrefix = $imagePrefix;
        $this->quality = $quality;
    }

    public function getPluginFolderName(): string
    {
        return $this->pluginFolderName;
    }

    public function getImagePrefix(): string
    {
        return $this->imagePrefix;
    }

    public function getQuality(): string
    {
        return $this->quality;
    }
}
