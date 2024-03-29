<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Card;

use amcsi\LyceeOverture\Card;
use amcsi\LyceeOverture\I18n\ManualTranslation\SuggestionResource;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Card $resource
 */
class CardResource extends JsonResource
{
    public function toArray($request)
    {
        $card = $this->resource;

        $rarities = collect(explode(',', $card->rarity));
        $variants = collect(explode(',', $card->variants));
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
            'rarity' => $rarities[0],
            'variants' => $rarities->map(fn(string $rarity, int $index) => [
                'rarity' => $rarity,
                'variant' => $variants[$index],
            ]),
            'translations' => CardTranslationResource::collection($card->translations),
            'created_at' => $card->created_at,
            'set' => new SetResource($this->whenLoaded('set')),
            'suggestions' => SuggestionResource::collection($this->whenLoaded('suggestions'))
        ];
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
