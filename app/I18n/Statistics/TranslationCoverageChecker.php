<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\Statistics;

use amcsi\LyceeOverture\Card\CardBuilderFactory;
use amcsi\LyceeOverture\Etc\Statistics;
use amcsi\LyceeOverture\I18n\Locale;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;

class TranslationCoverageChecker
{
    public function __construct(private CardBuilderFactory $cardBuilderFactory)
    {
    }

    public function calculateStatistics(array $query): Statistics
    {
        $statisticsResult = $this->fetchSomeStatistics($query);
        $cardCount = (int) $statisticsResult->total;
        $fullyTranslatedCount = (int) $statisticsResult->fully_translated;
        $ratioOfFullyTranslated = $cardCount ? $fullyTranslatedCount / $cardCount : 0;
        return new Statistics(
            (float) $statisticsResult->kanji_removal_ratio,
            $ratioOfFullyTranslated,
            $fullyTranslatedCount,
            $cardCount
        );
    }

    /**
     * Returns the total amount of cards.
     */
    public function fetchSomeStatistics(array $query): \stdClass
    {
        $fullyTranslatedQuery = "sum(case when t.kanji_count=0 or s.locale='en' then 1 else 0 end)";
        return $this->getBuilder(Locale::ENGLISH, $query)
            ->join(
                'card_translations as t_ja',
                function (JoinClause $join): void {
                    $join->on('t_ja.card_id', '=', 'cards.id')->where('t_ja.locale', '=', Locale::JAPANESE);
                }
            )
            ->select(\DB::raw("count(*) as total"))
            ->addSelect(\DB::raw("$fullyTranslatedQuery as fully_translated"))
            ->addSelect(\DB::raw("
                case
                when SUM(t_ja.kanji_count) > 0 
                then 1 - SUM(IF(s.locale = 'en', 0, t.kanji_count)) / SUM(t_ja.kanji_count)
                else 0
                end
                as kanji_removal_ratio"
            ))
            ->toBase()
            ->sole();
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
