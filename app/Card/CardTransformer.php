<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Card;

use amcsi\LyceeOverture\Card;
use amcsi\LyceeOverture\CardTranslation;
use League\Fractal\TransformerAbstract;

class CardTransformer extends TransformerAbstract
{
    private $cardTranslationTransformer;

    public function __construct(CardTranslationTransformer $cardTranslationTransformer)
    {
        $this->cardTranslationTransformer = $cardTranslationTransformer;
    }

    public function transform(Card $card)
    {
        /** @var CardTranslation $cardTranslation */
        $cardTranslation = $card->getTranslation();
        return [
            'id' => $card->id,
            'type' => $card->getType(),
            'ex' => $card->ex,
            'dmg' => $card->dmg,
            'ap' => $card->ap,
            'dp' => $card->dp,
            'sp' => $card->sp,
            'translation' => $this->cardTranslationTransformer->transform($cardTranslation),
        ];
    }
}
