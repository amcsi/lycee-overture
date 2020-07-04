<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\ManualTranslation;

use amcsi\LyceeOverture\CardTranslation;
use amcsi\LyceeOverture\Suggestion;
use Illuminate\Support\Arr;

class SuggestionApprover
{
    public function approve(Suggestion $suggestion): void
    {
        $locale = $suggestion->locale;
        $keyAttributes = ['card_id', 'locale'];
        $attributes = $suggestion->only($keyAttributes);
        $values = $suggestion->attributesToArray();

        $newTranslationData = $suggestion->card->getTranslation("$locale-auto")->replicate()->toArray();

        // Grab all the auto-translated values, and merge in the card description related properties
        // that are in the translation suggestion.
        $tranlationValues = Arr::except($newTranslationData, $keyAttributes);
        $tranlationValues = array_replace(
            $tranlationValues,
            Arr::only($values, Suggestion::SUGGESTABLE_PROPERTIES)
        );
        CardTranslation::updateOrInsert($attributes, $tranlationValues);

        $suggestion->delete();
    }
}
