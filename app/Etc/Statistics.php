<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Etc;

class Statistics
{
    private $kanjiRemovalRatio;
    private $fullyTranslatedRatio;
    private $translatedCards;
    private $totalCards;

    public function __construct(
        float $kanjiRemovalRatio,
        float $fullyTranslatedRatio,
        int $translatedCards,
        int $totalCards
    ) {
        $this->kanjiRemovalRatio = $kanjiRemovalRatio;
        $this->fullyTranslatedRatio = $fullyTranslatedRatio;
        $this->translatedCards = $translatedCards;
        $this->totalCards = $totalCards;
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
