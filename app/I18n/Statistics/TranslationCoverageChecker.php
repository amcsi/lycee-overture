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
    public function __construct(
        private CardTranslation $cardTranslation,
        private CardBuilderFactory $cardBuilderFactory
    ) {
    }

    public function calculateStatistics(array $query): Statistics
    {
        $cardCount = $this->countCards($query);
        $fullyTranslatedCount = $this->countFullyTranslated($query);
        $ratioOfFullyTranslated = $cardCount ? $fullyTranslatedCount / $cardCount : 0;
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
                $join->on('t_ja.card_id', '=', 'cards.id')->where('t_ja.locale', '=', Locale::JAPANESE);
            }
        );
        $builder->select(\DB::raw('SUM(t_ja.kanji_count) as before_count'));
        $builder->addSelect(\DB::raw("SUM(IF(s.locale = 'en', 0, t.kanji_count)) as after_count"));
        $result = $builder->sole();
        $japaneseKanjiCount = $result->before_count;
        $afterEnglishKanjiCount = $result->after_count;
        return $japaneseKanjiCount ? 1 - $afterEnglishKanjiCount / $japaneseKanjiCount : 0;
    }

    public function calculateRatioOfFullyTranslated(array $query = []): float
    {
        $cardCount = $this->countCards($query);
        return $cardCount ? $this->countFullyTranslated($query) / $cardCount : 0;
    }

    /**
     * Counts the number of fully translated cards.
     */
    public function countFullyTranslated(array $query = []): int
    {
        $englishBuilder = $this->getBuilder(Locale::ENGLISH, $query);
        return $englishBuilder->where(function (Builder $builder) {
            $builder->where('kanji_count', '=', '0')
                ->orWhere('s.locale', '=', Locale::ENGLISH);
        })->count();
    }

    /**
     * Returns the total amount of cards.
     */
    public function countCards(array $query): int
    {
        return $this->getBuilder(Locale::ENGLISH, $query)->count();
    }

    private function getBuilder(string $locale, array $query): Builder
    {
        return $this->cardBuilderFactory
            ->createBuilderWithQuery($locale, $query, true)
            ->leftJoin(
                'suggestions as s',
                'cards.id',
                '=',
                's.card_id'
            );
    }
}
