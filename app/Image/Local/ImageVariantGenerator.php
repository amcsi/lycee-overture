<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Image\Local;

use amcsi\LyceeOverture\Image\ImageFormat;
use GdImage;
use Psr\Log\LoggerInterface;
use RuntimeException;

/**
 * Writes the local files of every ImageFormat of a source image.
 */
class ImageVariantGenerator
{
    /** Candidates of the automatically chosen file, in the order the web server tries them. */
    public const AUTO_EXTENSIONS = ['avif', 'webp', 'jpg'];

    /** @var string[] */
    private array $autoEncoders;

    /**
     * @param string[]|null $autoEncoders Extensions competing for the automatically chosen file. Defaults to all of
     *                                    AUTO_EXTENSIONS that GD can encode.
     */
    public function __construct(private string $outputDir, private LoggerInterface $logger, ?array $autoEncoders = null)
    {
        $this->autoEncoders = $autoEncoders ?? $this->supportedEncoders();
    }

    /**
     * @param string $path Image path without an extension, e.g. "cards/LO-0001".
     * @return int Number of variants written; up-to-date ones are skipped unless forced.
     */
    public function generate(string $path, string $sourceFile, bool $force = false): int
    {
        $sourceModified = filemtime($sourceFile);
        $source = null;
        $written = 0;
        foreach (ImageFormat::cases() as $format) {
            $spec = VariantSpec::for($format);
            $base = "$this->outputDir/$spec->directory/$path";
            if (!$force && $this->isUpToDate($base, $spec, $sourceModified)) {
                continue;
            }
            $source ??= $this->load($sourceFile);
            $this->write($base, $spec, $source);
            ++$written;
        }
        return $written;
    }

    private function isUpToDate(string $base, VariantSpec $spec, int $sourceModified): bool
    {
        $files = [];
        if ($spec->auto) {
            $files = array_filter(
                array_map(fn($ext) => "$base.$ext", self::AUTO_EXTENSIONS),
                'is_file',
            );
            if (!$files) {
                return false;
            }
        }
        if ($spec->jpeg) {
            $files[] = "$base.jpg";
        }
        foreach ($files as $file) {
            if (!is_file($file) || filemtime($file) < $sourceModified) {
                return false;
            }
        }
        return true;
    }

    private function write(string $base, VariantSpec $spec, GdImage $source): void
    {
        $image = $this->resize($source, $spec);

        $encoded = [];
        foreach ($spec->auto ? $this->autoEncoders : [] as $ext) {
            $encoded[$ext] = $this->encode($image, $ext);
        }
        $keep = [];
        if ($encoded) {
            $sizes = array_map('strlen', $encoded);
            asort($sizes);
            $keep[] = array_key_first($sizes);
        }
        if ($spec->jpeg) {
            $encoded['jpg'] ??= $this->encode($image, 'jpg');
            $keep[] = 'jpg';
        }

        $directory = dirname($base);
        if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
            throw new RuntimeException("Could not create directory: $directory");
        }
        foreach (self::AUTO_EXTENSIONS as $ext) {
            $file = "$base.$ext";
            if (in_array($ext, $keep, true)) {
                // Write then rename, so the web server never serves a half-written file.
                $temporary = "$file.tmp-" . getmypid();
                file_put_contents($temporary, $encoded[$ext]);
                rename($temporary, $file);
            } elseif (is_file($file)) {
                unlink($file);
            }
        }
    }

    private function load(string $file): GdImage
    {
        $image = @imagecreatefromstring((string) file_get_contents($file));
        if (!$image) {
            throw new RuntimeException("Could not read image: $file");
        }
        imagepalettetotruecolor($image);
        imagealphablending($image, false);
        imagesavealpha($image, true);
        return $image;
    }

    private function resize(GdImage $source, VariantSpec $spec): GdImage
    {
        $width = imagesx($source);
        $height = imagesy($source);
        if ($spec->height !== null && $spec->height < $height) {
            $newHeight = $spec->height;
            $newWidth = max(1, (int) round($width * $newHeight / $height));
        } elseif ($spec->width !== null && $spec->width < $width) {
            $newWidth = $spec->width;
            $newHeight = max(1, (int) round($height * $newWidth / $width));
        } else {
            return $source;
        }

        $resized = imagecreatetruecolor($newWidth, $newHeight);
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        return $resized;
    }

    private function encode(GdImage $image, string $ext): string
    {
        ob_start();
        $success = match ($ext) {
            'jpg' => imagejpeg($this->flattenOntoWhite($image), null, 85),
            'webp' => imagewebp($image, null, 82),
            'avif' => imageavif($image, null, 60, 6),
        };
        $data = (string) ob_get_clean();
        if (!$success || $data === '') {
            throw new RuntimeException("Could not encode image as $ext");
        }
        return $data;
    }

    private function flattenOntoWhite(GdImage $image): GdImage
    {
        $flat = imagecreatetruecolor(imagesx($image), imagesy($image));
        imagefill($flat, 0, 0, imagecolorallocate($flat, 255, 255, 255));
        imagealphablending($flat, true);
        imagecopy($flat, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
        return $flat;
    }

    /** @return string[] */
    private function supportedEncoders(): array
    {
        $types = ['avif' => IMG_AVIF, 'webp' => IMG_WEBP, 'jpg' => IMG_JPG];
        $supported = [];
        foreach ($types as $ext => $type) {
            if (imagetypes() & $type) {
                $supported[] = $ext;
            } else {
                $this->logger->warning("GD can't encode $ext; it won't be a candidate for image variants.");
            }
        }
        return $supported;
    }
}
