<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Image;

/**
 * Lets the frontend build image URLs without its own ImageUrlBuilder implementation: it gets one URL template per
 * ImageFormat case name, and replaces the placeholder with the source image path.
 */
class ImageUrlTemplates
{
    public const PATH_PLACEHOLDER = '{path}';

    /**
     * @return array<string, string> Template URLs keyed by ImageFormat case name.
     */
    public static function build(ImageUrlBuilder $builder): array
    {
        $templates = [];
        foreach (ImageFormat::cases() as $format) {
            $templates[$format->name] = $builder->url(self::PATH_PLACEHOLDER, $format);
        }
        return $templates;
    }
}
