<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Card;

use amcsi\LyceeOverture\CardSet;
use amcsi\LyceeOverture\I18n\Locale;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property CardSet $resource
 */
class CardSetResource extends JsonResource
{
    public function toArray($request): array
    {
        $cardSet = $this->resource;
        return [
            'id' => $cardSet->id,
            'name' => \App::getLocale() === Locale::JAPANESE ? $cardSet->name_ja : $cardSet->name_en,
        ];
    }
}
