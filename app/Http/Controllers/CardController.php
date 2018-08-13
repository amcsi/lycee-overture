<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Http\Controllers;

use amcsi\LyceeOverture\Card;
use amcsi\LyceeOverture\Card\CardTransformer;
use amcsi\LyceeOverture\I18n\Locale;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function index(CardTransformer $cardTransformer, Request $request)
    {
        $locale = \App::getLocale();

        /** @var Builder $builder */
        $builder = Card::select(['cards.*'])
            ->join(
                'card_translations as t',
                function (JoinClause $join) {
                    $join->on('cards.id', '=', 't.card_id')
                        ->where('t.locale', '=', 'en');
                }
            );

        if ($set = $request->get('set')) {
            $builder->join(
                'card_sets AS cs',
                function (JoinClause $join) use ($set): void {
                    $join
                        ->on(new Expression('FIND_IN_SET(cards.id, cs.cards)'), '>', new Expression('0'))
                        ->where('cs.id', '=', $set);
                }
            );
        }

        if ($locale !== Locale::JAPANESE) {
            // Bring forward cards with fewer kanjis (i.e. fewer untranslated bits).
            // Of course this is only necessary if the locale is non-Japanese.
            $builder->orderBy('t.kanji_count', 'asc');
        }
        $builder->orderBy('id', 'asc');
        $cards = $builder->paginate(50);

        return $this->response->paginator($cards, $cardTransformer);
    }
}
