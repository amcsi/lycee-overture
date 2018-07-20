<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\Statistics;

use amcsi\LyceeOverture\CardTranslation;
use amcsi\LyceeOverture\I18n\Locale;
use Illuminate\Database\Eloquent\Builder;

class TranslationCoverageChecker
{
    private $cardTranslation;

    public function __construct(CardTranslation $cardTranslation)
    {
        $this->cardTranslation = $cardTranslation;
    }

    public function calculateRatioOfJapaneseCharacterRemoval(): float
    {
        $japaneseBuilder = $this->getBuilder(Locale::JAPANESE);
        $englishBuilder = $this->getBuilder(Locale::ENGLISH);
        $japaneseKanjiCount = $japaneseBuilder->sum('kanji_count') ?: 0;
        $afterEnglishKanjiCount = $englishBuilder->sum('kanji_count') ?: $japaneseKanjiCount;
        return 1 - $afterEnglishKanjiCount / $japaneseKanjiCount;
    }

    public function calculateRatioOfFullyTranslated(): float
    {
        return $this->countFullyTranslated() / $this->countCards();
    }

    /**
     * Counts the number of fully translated cards.
     */
    public function countFullyTranslated(): int
    {
        $englishBuilder = $this->getBuilder(Locale::ENGLISH);
        return $englishBuilder->where('kanji_count', '=', '0')->count();
    }

    /**
     * Returns the total amount of cards.
     */
    public function countCards(): int
    {
        $englishBuilder = $this->getBuilder(Locale::JAPANESE);
        return $englishBuilder->count();
    }

    private function getBuilder(string $locale): Builder
    {
        return $this->cardTranslation->newQuery()->where('locale', $locale);
    }
}
