<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Etc;

class Statistics
{
    public function __construct(
        private float $kanjiRemovalRatio,
        private float $fullyTranslatedRatio,
        private int $translatedCards,
        private int $totalCards
    ) {
    }

    public function getKanjiRemovalRatio(): float
    {
        return $this->kanjiRemovalRatio;
    }

    public function getFullyTranslatedRatio(): float
    {
        return $this->fullyTranslatedRatio;
    }

    public function getTranslatedCards(): int
    {
        return $this->translatedCards;
    }

    public function getTotalCards(): int
    {
        return $this->totalCards;
    }
}
