<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Card;

use amcsi\LyceeOverture\CardDeck;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property CardDeck $resource
 */
class CardDeckResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'card' => new CardResource($this->resource->card),
            'quantity' => $this->resource->quantity,
        ];
    }
}
