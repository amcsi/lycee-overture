<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\ManualTranslation;

use amcsi\LyceeOverture\CardTranslation;
use amcsi\LyceeOverture\Suggestion;

class ManualAutoDifferenceChecker
{
    public function areSuggestablesDifferent(
        CardTranslation $translation1,
        CardTranslation $translation2
    ): bool {
        return (bool) array_diff(
            $translation1->only(Suggestion::SUGGESTABLE_PROPERTIES),
            $translation2->only(Suggestion::SUGGESTABLE_PROPERTIES)
        );
    }
}
