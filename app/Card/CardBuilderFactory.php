<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Card;

use amcsi\LyceeOverture\Card;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Query\JoinClause;

class CardBuilderFactory
{
    private $card;
    private BrandMapper $brandMapper;

    public function __construct(Card $card, BrandMapper $brandMapper)
    {
        $this->card = $card;
        $this->brandMapper = $brandMapper;
    }

    public function createBuilderWithQuery(string $locale, array $query): Builder
    {
        /** @var Builder $builder */
        $builder = $this->card->select(['cards.*'])
            ->leftJoin(
                'card_translations as t',
                function (JoinClause $join) use ($locale) {
                    $join->on('cards.id', '=', 't.card_id')
                        ->where('t.locale', '=', $locale);
                }
            );

        if ($deck = ($query['deck'] ?? null)) {
            $builder->join(
                'card_sets AS cs',
                function (JoinClause $join) use ($deck): void {
                    $join
                        ->on(new Expression('FIND_IN_SET(cards.id, cs.cards)'), '>', new Expression('0'))
                        ->where('cs.id', '=', $deck);
                }
            );
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

        if (($set = $query['set'] ?? null)) {
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
                        ->orWhere('t.ability_name', 'LIKE', $like);
                }
            );
        }

        if ($text = (string) ($query['text'] ?? null)) {
            $builder->where(
                function (Builder $whereBuilder) use ($text) {
                    $like = '%' . self::escapeLike($text) . '%';
                    $whereBuilder
                        ->where('t.ability_description', 'LIKE', $like)
                        ->orWhere('t.comments', 'LIKE', $like);
                }
            );
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
