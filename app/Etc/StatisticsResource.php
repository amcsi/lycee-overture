<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Etc;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Statistics $resource
 */
class StatisticsResource extends JsonResource
{
    public function toArray($request)
    {
        $statistics = $this->resource;
        return [
            'kanji_removal_ratio' => $statistics->getKanjiRemovalRatio(),
            'fully_translated_ratio' => $statistics->getFullyTranslatedRatio(),
            'translated_cards' => $statistics->getTranslatedCards(),
            'total_cards' => $statistics->getTotalCards(),
        ];
    }
}
