<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Image;

interface ImageUrlBuilder
{
    /**
     * @param string $path Source image path without an extension, e.g. "cards/LO-0001". Implementations must insert it
     *                     verbatim, as ImageUrlTemplates relies on that to build URL templates for the frontend.
     */
    public function url(string $path, ImageFormat $format): string;

    /**
     * URL of a folder that clients append "<name>.jpg" to, e.g. LackeyCCG's CardGeneralURLs.
     */
    public function folderUrl(string $folder, ImageFormat $format): string;
}
