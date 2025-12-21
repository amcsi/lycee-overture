<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Etc\Lackey;

use amcsi\LyceeOverture\Models\Card;
use amcsi\LyceeOverture\I18n\Locale;

class LackeyNameFormatter
{
    public static function formatSet(Card $card): string
    {
        return str_replace(
            ' ',
            '_',
            $card->set ? $card->set->getFullName(Locale::ENGLISH) : 'Unknown'
        );
    }
}
