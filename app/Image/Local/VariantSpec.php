<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Image\Local;

use amcsi\LyceeOverture\Image\ImageFormat;

/**
 * How an ImageFormat is stored locally.
 */
final readonly class VariantSpec
{
    /**
     * @param bool $auto Whether to write the smallest of the supported formats, for browsers.
     * @param bool $jpeg Whether a .jpg must always exist, for clients that need real JPEGs.
     */
    private function __construct(
        public string $directory,
        public ?int $height,
        public ?int $width,
        public bool $auto,
        public bool $jpeg,
    ) {
    }

    public static function for(ImageFormat $format): self
    {
        return match ($format) {
            ImageFormat::Full => new self('full', null, null, auto: true, jpeg: true),
            ImageFormat::Height150 => new self('h150', 150, null, auto: true, jpeg: false),
            ImageFormat::Height300 => new self('h300', 300, null, auto: true, jpeg: false),
            ImageFormat::Height520 => new self('h520', 520, null, auto: true, jpeg: false),
            ImageFormat::Width281 => new self('w281', null, 281, auto: false, jpeg: true),
        };
    }
}
