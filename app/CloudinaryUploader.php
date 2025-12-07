<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture;

use Cloudinary\Api\Upload\UploadApi;

class CloudinaryUploader
{
    public function upload(string $filename, string $publicId, array $params = []): void
    {
        $params = array_replace(config('cloudinary.defaults'), $params);
        $params['public_id'] = $publicId;

        (new UploadApi())->upload($filename, $params);
    }
}
