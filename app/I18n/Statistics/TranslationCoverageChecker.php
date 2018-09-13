<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\Statistics;

use amcsi\LyceeOverture\Card\CardBuilderFactory;
use amcsi\LyceeOverture\CardTranslation;
use amcsi\LyceeOverture\Etc\Statistics;
use amcsi\LyceeOverture\I18n\Locale;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;

class TranslationCoverageChecker
{
    private $cardTranslation;
    private $cardBuilderFactory;

    public function __construct(CardTranslation $cardTranslation, CardBuilderFactory $cardBuilderFactory)
    {
        $this->cardTranslation = $cardTranslation;
        $this->cardBuilderFactory = $cardBuilderFactory;
    }

    public function calculateStatistics(array $query): Statistics
    {
        $cardCount = $this->countCards($query);
        $fullyTranslatedCount = $this->countFullyTranslated($query);
        $ratioOfFullyTranslated = $fullyTranslatedCount / $cardCount;
        $ratioOfJapaneseCharacterRemoval = $this->calculateRatioOfJapaneseCharacterRemoval($query);
        return new Statistics(
            $ratioOfJapaneseCharacterRemoval,
            $ratioOfFullyTranslated,
            $fullyTranslatedCount,
            $cardCount
        );
    }

    public function calculateRatioOfJapaneseCharacterRemoval(array $query = []): float
    {
        $builder = $this->getBuilder(Locale::ENGLISH, $query);

        $builder->join(
            'card_translations as t_ja',
            function (JoinClause $join): void {
                $join->on('t.card_id', '=', 'cards.id')->where('t_ja.locale', '=', 'ja');
            }
        );
        $japaneseKanjiCount = $builder->sum('t_ja.kanji_count');
        $afterEnglishKanjiCount = $builder->sum('t.kanji_count');
        return 1 - $afterEnglishKanjiCount / $japaneseKanjiCount;
    }

    public function calculateRatioOfFullyTranslated(array $query = []): float
    {
        return $this->countFullyTranslated($query) / $this->countCards($query);
    }

    /**
     * Counts the number of fully translated cards.
     */
    public function countFullyTranslated(array $query = []): int
    {
        $englishBuilder = $this->getBuilder(Locale::ENGLISH, $query);
        return $englishBuilder->where('kanji_count', '=', '0')->count();
    }

    /**
     * Returns the total amount of cards.
     */
    public function countCards(array $query): int
    {
        $englishBuilder = $this->getBuilder(Locale::JAPANESE, $query);
        return $englishBuilder->count();
    }

    private function getBuilder(string $locale, array $query): Builder
    {
        return $this->cardBuilderFactory->createBuilderWithQuery($locale, $query);
    }
}
