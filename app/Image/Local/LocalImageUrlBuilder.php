<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Image\Local;

use amcsi\LyceeOverture\Image\ImageFormat;
use amcsi\LyceeOverture\Image\ImageUrlBuilder;
use InvalidArgumentException;

/**
 * URLs of the variants written by ImageVariantGenerator. Extensionless URLs are resolved to the best existing file by
 * the web server (see LocalImageFiles).
 */
class LocalImageUrlBuilder implements ImageUrlBuilder
{
    public function __construct(private string $baseUrl)
    {
    }

    public function url(string $path, ImageFormat $format): string
    {
        return "{$this->directoryUrl($format)}/$path";
    }

    public function jpegUrl(string $path, ImageFormat $format): string
    {
        if (!VariantSpec::for($format)->jpeg) {
            throw new InvalidArgumentException("$format->name has no JPEG variant.");
        }
        return $this->url($path, $format) . '.jpg';
    }

    public function folderUrl(string $folder, ImageFormat $format): string
    {
        return "{$this->directoryUrl($format)}/$folder/";
    }

    private function directoryUrl(ImageFormat $format): string
    {
        return rtrim($this->baseUrl, '/') . '/' . VariantSpec::for($format)->directory;
    }
}
