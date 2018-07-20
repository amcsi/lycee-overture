<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Etc;

use amcsi\LyceeOverture\I18n\Statistics\TranslationCoverageChecker;
use League\Fractal\TransformerAbstract;

class StatisticsTransformer extends TransformerAbstract
{
    public function transform(TranslationCoverageChecker $translationCoverageChecker)
    {
        return [
            'kanji_removal_ratio' => $translationCoverageChecker->calculateRatioOfJapaneseCharacterRemoval(),
            'fully_translated_ratio' => $translationCoverageChecker->calculateRatioOfFullyTranslated(),
            'translated_cards' => $translationCoverageChecker->countFullyTranslated(),
            'total_cards' => $translationCoverageChecker->countCards(),
        ];
    }
}
