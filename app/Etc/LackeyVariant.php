<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Etc;

use amcsi\LyceeOverture\Image\ImageFormat;

class LackeyVariant
{
    public function __construct(private string $pluginFolderName, private ImageFormat $imageFormat, private string $quality)
    {
    }

    public function getPluginFolderName(): string
    {
        return $this->pluginFolderName;
    }

    public function getImageFormat(): ImageFormat
    {
        return $this->imageFormat;
    }

    public function getQuality(): string
    {
        return $this->quality;
    }
}
