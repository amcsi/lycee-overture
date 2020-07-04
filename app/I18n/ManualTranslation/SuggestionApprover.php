<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\ManualTranslation;

use amcsi\LyceeOverture\CardTranslation;
use amcsi\LyceeOverture\Suggestion;
use Illuminate\Support\Arr;

class SuggestionApprover
{
    private ManualAutoDifferenceChecker $manualAutoDifferenceChecker;

    public function __construct(ManualAutoDifferenceChecker $manualAutoDifferenceChecker)
    {
        $this->manualAutoDifferenceChecker = $manualAutoDifferenceChecker;
    }

    public function approve(Suggestion $suggestion): void
    {
        $locale = $suggestion->locale;
        $keyAttributes = ['card_id', 'locale'];
        $attributes = $suggestion->only($keyAttributes);
        $values = $suggestion->attributesToArray();

        $cardAutoTranslation = $suggestion->card->getTranslation("$locale-auto");
        $newTranslationData = $cardAutoTranslation->replicate()->toArray();

        // Grab all the auto-translated values, and merge in the card description related properties
        // that are in the translation suggestion.
        $tranlationValues = Arr::except($newTranslationData, $keyAttributes);
        $tranlationValues = array_replace(
            $tranlationValues,
            Arr::only($values, Suggestion::SUGGESTABLE_PROPERTIES)
        );
        $cardTranslation = CardTranslation::unguarded(
            fn() => CardTranslation::firstOrNew($attributes)->fill($tranlationValues)
        );

        if ($this->manualAutoDifferenceChecker->areSuggestablesDifferent($cardTranslation, $cardAutoTranslation)) {
            // Save the manual translation.
            $cardTranslation->save();
        } else {
            // Since being equal to the auto-translation, delete the manual translation.
            $cardTranslation->delete();
        }

        $suggestion->delete();
    }
}
