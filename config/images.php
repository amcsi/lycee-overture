<?php
declare(strict_types=1);

return [
    // Which ImageUrlBuilder builds image URLs: "cloudinary" or "local".
    'url_builder' => env('IMAGE_URL_BUILDER', 'cloudinary'),
    'base_url' => env('IMAGE_BASE_URL', rtrim((string) env('APP_URL'), '/') . '/storage/images'),
    'output_path' => storage_path('app/public/images'),
    'static_sources_path' => resource_path('image-sources'),
];
