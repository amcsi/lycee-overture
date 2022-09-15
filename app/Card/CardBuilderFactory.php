<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Card;

use amcsi\LyceeOverture\Card;
use amcsi\LyceeOverture\CardDeck;
use amcsi\LyceeOverture\CardTranslation;
use amcsi\LyceeOverture\I18n\Locale;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;

class CardBuilderFactory
{
    public function __construct(private Card $card, private BrandMapper $brandMapper)
    {
    }

    public function createBuilderWithQuery(string $locale, array $query, $forceLoadTranslation = false): Builder
    {
        /** @var Builder $builder */
        $builder = $this->card->select(['cards.*']);

        $isNotForJapanese = $locale !== Locale::JAPANESE;

        if ($deck = ($query['deck'] ?? null)) {
            $builder->whereIn('cards.id', CardDeck::where('deck_id', $deck)->get()->pluck('card_id'));
        }

        if (($brand = $query['brand'] ?? null)) {
            if ($brand === '-1') {
                // Unknown/no brand.
                $setIds = $this->brandMapper->fetchSetIdsOfUnknownBrands();
            } else {
                $setIds = $this->brandMapper->fetchSetIdsByBrands((array) $query['brand']);
            }
            $query['set'] = ($query['set'] ?? null) ? array_intersect($setIds, (array) $query['set']) : $setIds;
        }

        if (($set = $query['set'] ?? null) !== null) {
            if ($set === '-1') {
                $builder->whereNull('set_id');
            } else {
                $builder->whereIn('set_id', (array) $set);
            }
        }

        if ($name = (string) ($query['name'] ?? null)) {
            $builder->where(
                function (Builder $whereBuilder) use ($name) {
                    $like = '%' . self::escapeLike($name) . '%';
                    $whereBuilder
                        ->where('t.name', 'LIKE', $like)
                        ->orWhere('t.ability_name', 'LIKE', $like)
                        ->orWhere('t.character_type', 'LIKE', $like);
                }
            );
        }

        $name = (string) ($query['name'] ?? null);
        $text = (string) ($query['text'] ?? null);
        $hideFullyTranslated = (bool) ($query['hideFullyTranslated'] ?? null);

        // Load some translations for text search or kanji counting?
        $shouldLoadTranslation = $forceLoadTranslation ||
            $name !== '' ||
            $text !== '' ||
            $hideFullyTranslated ||
            ($isNotForJapanese && ($query['translatedFirst'] ?? null));
        if ($shouldLoadTranslation) {
            $nameColumns = CardTranslation::NAME_COLUMNS;
            $textColumns = CardTranslation::TEXT_COLUMNS;

            $builder->join(
                'card_translations as t',
                function (JoinClause $join) use ($text, $name, $nameColumns, $textColumns, $locale) {
                    // The locales we want to look at.
                    $locales = [$locale];
                    if ($locale !== Locale::JAPANESE) {
                        $locales[] = "$locale-auto";
                    }
                    // Base join condition.
                    $join->on('cards.id', '=', 't.card_id')->whereIn('locale', $locales);

                    // Additional search conditions.
                    if ($name) {
                        $join->where(
                            function (JoinClause $whereBuilder) use ($name, $nameColumns) {
                                $like = '%' . self::escapeLike($name) . '%';
                                foreach ($nameColumns as $column) {
                                    $whereBuilder->orWhere("t.$column", 'LIKE', $like);
                                }
                            }
                        );
                    }
                    if ($text) {
                        $join->where(
                            function (JoinClause $whereBuilder) use ($text, $textColumns) {
                                $like = '%' . self::escapeLike($text) . '%';
                                foreach ($textColumns as $column) {
                                    $whereBuilder->orWhere("t.$column", 'LIKE', $like);
                                }
                            }
                        );
                    }
                }
            );
            if ($isNotForJapanese) {
                // Prefer fully translated over auto translated.
                $builder->joinSub(
                    CardTranslation::select([
                        'card_id',
                        \DB::raw('MIN(locale) as preferred_locale'),
                    ])->groupBy('card_id'),
                    't2',
                    function (JoinClause $join) {
                        $join->on('t.card_id', '=', 't2.card_id')->on('t.locale', '=', 't2.preferred_locale');
                    }
                );
            }
            if ($hideFullyTranslated) {
                $builder->where('t.kanji_count', '!=', 0);
            }
        }

        if ($cardId = ($query['cardId'] ?? null)) {
            // Card IDs are comma-separated, and only the number bits from each value matters,
            // so the LO- and padding numbers are optional.
            $cardIds = array_map(
                function (string $cardId): string {
                    return sprintf('LO-%04d', preg_replace('/\D/', '', $cardId));
                },
                explode(',', $cardId)
            );
            $builder->whereIn('cards.id', $cardIds);
        }

        if (!empty($query['translationSuggestions'])) {
            $builder->has('suggestions');
        }

        return $builder;
    }

    private static function escapeLike(string $value): string
    {
        $char = '\\';
        return str_replace(
            [$char, '%', '_'],
            [$char . $char, $char . '%', $char . '_'],
            $value
        );
    }
}
