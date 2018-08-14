<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Etc;

use amcsi\LyceeOverture\I18n\Statistics\TranslationCoverageChecker;
use League\Fractal\TransformerAbstract;

class StatisticsTransformer extends TransformerAbstract
{
    public function transform(Statistics $statistics)
    {
        return [
            'kanji_removal_ratio' => $statistics->getKanjiRemovalRatio(),
            'fully_translated_ratio' => $statistics->getFullyTranslatedRatio(),
            'translated_cards' => $statistics->getTranslatedCards(),
            'total_cards' => $statistics->getTotalCards(),
        ];
    }
}
