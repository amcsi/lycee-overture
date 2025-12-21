<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Card;

use amcsi\LyceeOverture\Models\CardTranslation;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property CardTranslation $resource
 */
class CardTranslationResource extends JsonResource
{
    public function toArray($request)
    {
        $cardTranslation = $this->resource;
        return [
            'name' => $cardTranslation->name,
            'ability_name' => $cardTranslation->ability_name,
            'basic_abilities' => $cardTranslation->basic_abilities,
            'pre_comments' => $cardTranslation->pre_comments,
            'ability_cost' => $cardTranslation->ability_cost,
            'ability_description' => $cardTranslation->ability_description,
            'comments' => $cardTranslation->comments,
            'character_type' => $cardTranslation->character_type,
            'locale' => $cardTranslation->locale,
            'kanji_count' => $cardTranslation->kanji_count,
        ];
    }
}
