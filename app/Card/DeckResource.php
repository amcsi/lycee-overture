<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Card;

use amcsi\LyceeOverture\Deck;
use amcsi\LyceeOverture\I18n\Locale;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Deck $resource
 */
class DeckResource extends JsonResource
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
