<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture;

use Cloudinary\Uploader;

class CloudinaryUploader
{
    public function upload(string $filename, string $publicId, array $params = []): void
    {
        $params = array_replace(config('cloudinary.defaults'), $params);
        $params['public_id'] = $publicId;

        Uploader::upload($filename, $params);
    }
}
