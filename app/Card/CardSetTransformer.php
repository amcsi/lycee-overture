<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Card;

use amcsi\LyceeOverture\CardSet;
use amcsi\LyceeOverture\I18n\Locale;
use League\Fractal\TransformerAbstract;

class CardSetTransformer extends TransformerAbstract
{
    public function transform(CardSet $cardSet): array
    {
        return [
            'id' => $cardSet->id,
            'name' => \LaravelLocalization::getCurrentLocale() === Locale::JAPANESE ? $cardSet->name_ja :
                $cardSet->name_en,
        ];
    }
}
