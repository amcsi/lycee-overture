<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\ManualTranslation;

use amcsi\LyceeOverture\Models\CardTranslation;
use amcsi\LyceeOverture\I18n\JapaneseCharacterCounter;
use amcsi\LyceeOverture\Models\Suggestion;
use Illuminate\Support\Arr;

class SuggestionApprover
{
    public function __construct(private ManualAutoDifferenceChecker $manualAutoDifferenceChecker)
    {
    }

    public function approve(Suggestion $suggestion): void
    {
        $locale = $suggestion->locale;
        $keyAttributes = ['card_id', 'locale'];
        $attributes = $suggestion->only($keyAttributes);
        $values = $suggestion->attributesToArray();

        $suggestion->loadMissing('card.translations');

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
        $cardTranslation->kanji_count = JapaneseCharacterCounter::countJapaneseCharactersForDbRow($tranlationValues);

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
