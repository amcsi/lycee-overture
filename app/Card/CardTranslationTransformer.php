<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Card;

use amcsi\LyceeOverture\CardTranslation;
use League\Fractal\TransformerAbstract;

class CardTranslationTransformer extends TransformerAbstract
{
    public function transform(CardTranslation $cardTranslation)
    {
        return [
            'name' => $cardTranslation->name,
            'ability_name' => $cardTranslation->ability_name,
            'basic_abilities' => $cardTranslation->basic_abilities,
            'ability_cost' => $cardTranslation->ability_cost,
            'ability_description' => $cardTranslation->ability_description,
            'comments' => $cardTranslation->comments,
        ];
    }
}
