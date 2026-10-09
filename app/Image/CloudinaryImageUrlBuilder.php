<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Image;

class CloudinaryImageUrlBuilder implements ImageUrlBuilder
{
    public function __construct(private string $cloudName)
    {
    }

    public function url(string $path, ImageFormat $format): string
    {
        return "{$this->baseUrl($format)}/$path.jpg";
    }

    public function jpegUrl(string $path, ImageFormat $format): string
    {
        // f_auto already serves JPEG to clients that don't advertise newer formats.
        return $this->url($path, $format);
    }

    public function folderUrl(string $folder, ImageFormat $format): string
    {
        return "{$this->baseUrl($format)}/$folder/";
    }

    private function baseUrl(ImageFormat $format): string
    {
        return "https://res.cloudinary.com/$this->cloudName/image/upload/$format->value";
    }
}
