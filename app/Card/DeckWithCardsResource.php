<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Card;

use amcsi\LyceeOverture\Deck;
use amcsi\LyceeOverture\I18n\Locale;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Deck $resource
 */
class DeckWithCardsResource extends DeckResource
{
    public function toArray($request): array
    {
        $ret = parent::toArray($request);
        $ret['cards'] = CardDeckResource::collection($this->resource->cards->map->pivot);
        return $ret;
    }
}
