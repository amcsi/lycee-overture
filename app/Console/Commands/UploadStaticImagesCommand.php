<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Console\Commands;

use amcsi\LyceeOverture\CloudinaryUploader;
use Illuminate\Console\Command;

class UploadStaticImagesCommand extends Command
{
    public const COMMAND = 'lycee:upload-static-images';

    protected $signature = self::COMMAND . ' {folder : Folder within resources/image-sources, e.g. "rules"}';
    protected $description = 'Uploads the images of a resources/image-sources folder to cloud service, keeping their paths.';

    public function handle(CloudinaryUploader $cloudinaryUploader): int
    {
        $folder = $this->argument('folder');
        $files = glob(resource_path("image-sources/$folder") . '/*');
        if (!$files) {
            $this->error("No images found in resources/image-sources/$folder");
            return self::FAILURE;
        }

        foreach ($files as $file) {
            $publicId = "$folder/" . pathinfo($file, PATHINFO_FILENAME);
            $this->output->text("Uploading $publicId...");
            $cloudinaryUploader->upload($file, $publicId);
        }

        return self::SUCCESS;
    }
}
