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
            'element' => self::getElementMarkup($card),
            'cost' => self::getCostMarkup($card),
            'translation' => $this->cardTranslationTransformer->transform($cardTranslation),
        ];
    }

    private static function getElementMarkup(Card $card): string
    {
        $coloredElements = Element::getElementKeys();
        $elementsMarkup = '';
        foreach ($coloredElements as $element) {
            if ($card->$element) {
                $elementsMarkup .= "[$element]";
            }
        }
        if (!$elementsMarkup) {
            // Default to showing a star as the element if lack of any colored elements.
            $elementsMarkup = '[star]';
        }
        return $elementsMarkup;
    }

    private static function getCostMarkup(Card $card): string
    {
        $markup = '';
        $elements = Element::getElementToMarkupMap();
        // Move 'star' to the end so that star costs appear at the end.
        $elements[] = array_shift($elements);

        foreach ($elements as $element) {
            $markup .= str_repeat("[$element]", $card->{"cost_$element"});
        }
        return $markup;
    }
}
