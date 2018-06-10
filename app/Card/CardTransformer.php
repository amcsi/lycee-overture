<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Card;

use amcsi\LyceeOverture\Card;
use League\Fractal\TransformerAbstract;

class CardTransformer extends TransformerAbstract
{
    public function transform(Card $card)
    {
        return [
            'type' => $card->getType(),
        ];
    }
}
