<?php
declare(strict_types=1);


namespace amcsi\LyceeOverture\Import;

class ImportConstants
{
    public const CSV_PATH = 'lycee.csv';
    public const ORIGINAL_CARD_IMAGES_PATH = 'images/original-cards';
    public const IMAGE_FILENAME = '{id}.png';
    public const IMAGE_URL = 'https://lycee-tcg.com/card/image/' . self::IMAGE_FILENAME;
}
