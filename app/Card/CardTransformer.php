<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Card;

use amcsi\LyceeOverture\Api\GenericTransformers\DateTimeTransformer;
use amcsi\LyceeOverture\Card;
use amcsi\LyceeOverture\I18n\Locale;
use League\Fractal\TransformerAbstract;

class CardTransformer extends TransformerAbstract
{
    private $cardTranslationTransformer;
    private $dateTimeTransformer;
    private $setTransformer;

    public function __construct(
        CardTranslationTransformer $cardTranslationTransformer,
        DateTimeTransformer $dateTimeTransformer,
        SetTransformer $setTransformer
    ) {
        $this->cardTranslationTransformer = $cardTranslationTransformer;
        $this->dateTimeTransformer = $dateTimeTransformer;
        $this->setTransformer = $setTransformer;
    }

    public function transform(Card $card)
    {
        $locale = \App::getLocale();
        $ret = [
            'id' => $card->id,
            'type' => $card->getType(),
            'ex' => $card->ex,
            'dmg' => $card->dmg,
            'ap' => $card->ap,
            'dp' => $card->dp,
            'sp' => $card->sp,
            'element' => self::getElementMarkup($card),
            'cost' => self::getCostMarkup($card),
            'rarity' => $card->rarity,
            'translation' => $locale !== Locale::JAPANESE ?
                $this->cardTranslationTransformer->transform($card->getBestTranslation()) :
                null,
            'japanese' => $this->cardTranslationTransformer->transform($card->getTranslation('ja')),
            'created_at' => $this->dateTimeTransformer->transform($card->created_at),
        ];
        if ($card->relationLoaded('set') && $card->set) {
            $ret['set'] = $this->setTransformer->transform($card->set);
        }
        return $ret;
    }

    public static function getElementMarkup(Card $card): string
    {
        $coloredElements = Element::getElementKeys();
        $elementsMarkup = '';
        foreach ($coloredElements as $element) {
            /** @noinspection PhpVariableVariableInspection */
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

    public static function getCostMarkup(Card $card): string
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
