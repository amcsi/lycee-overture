<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Card\Image;

use amcsi\LyceeOverture\Models\CardImage;
use amcsi\LyceeOverture\CloudinaryUploader;
use amcsi\LyceeOverture\Import\ImageDownloader;
use amcsi\LyceeOverture\Import\ImportConstants;
use Cake\Chronos\Chronos;

class CardImageCloudinaryUploader
{
    public function __construct(private CloudinaryUploader $cloudinaryUploader, private CardImage $cardImage)
    {
    }

    /**
     * @param string $cardVariant e.g. 'LO-0001A'
     * @param CardImage[] $cardImages Cached list of card images keyed by ID to check whether uploading is necessary.
     * @return string Message to log
     */
    public function upload(string $cardVariant, $cardImages): string
    {
        $localImagePath = storage_path(
            ImportConstants::ORIGINAL_CARD_IMAGES_PATH . '/' . ImageDownloader::getLocalImagePathForCardId($cardVariant)
        );
        $md5 = md5_file($localImagePath);
        if (
            isset($cardImages[$cardVariant]) &&
            $cardImages[$cardVariant]->getLastUploaded() &&
            $cardImages[$cardVariant]->getMd5() === $md5
        ) {
            // No need to upload.
            return 'Already uploaded.';
        }

        $this->cloudinaryUploader->upload($localImagePath, $cardVariant, ['folder' => 'cards', 'tags' => 'cards']);
        $values = ['md5' => $md5, 'last_uploaded' => new Chronos(), 'card_id' => $cardVariant];
        $this->cardImage->updateOrInsert(['card_id' => $cardVariant], $values);

        return 'Done.';
    }
}
