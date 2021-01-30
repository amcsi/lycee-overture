<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Card\Image;

use amcsi\LyceeOverture\Card;
use amcsi\LyceeOverture\CardImage;
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
     * @param Card $card The card whose image is to be uploaded.
     * @param CardImage[] $cardImages Cached list of card images keyed by ID to check whether uploading is necessary.
     * @return string Message to log
     */
    public function upload(Card $card, $cardImages): string
    {
        $id = $card->getId();
        $localImagePath = storage_path(
            ImportConstants::ORIGINAL_CARD_IMAGES_PATH . '/' . ImageDownloader::getLocalImagePathForCardId($id)
        );
        $md5 = md5_file($localImagePath);
        if (
            isset($cardImages[$id]) &&
            $cardImages[$id]->getLastUploaded() &&
            $cardImages[$id]->getMd5() === $md5
        ) {
            // No need to upload.
            return 'Already uploaded.';
        }

        $this->cloudinaryUploader->upload($localImagePath, $id, ['folder' => 'cards', 'tags' => 'cards']);
        $values = ['md5' => $md5, 'last_uploaded' => new Chronos(), 'card_id' => $id];
        $this->cardImage->updateOrInsert(['card_id' => $id], $values);

        return 'Done.';
    }
}
