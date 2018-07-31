<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Http\Controllers;

use amcsi\LyceeOverture\Card;
use amcsi\LyceeOverture\Card\CardTransformer;
use Illuminate\Database\Query\JoinClause;

class CardController extends Controller
{
    public function index(CardTransformer $cardTransformer)
    {
        $cards = Card::select(['cards.*'])
            ->join(
                'card_translations as t',
                function (JoinClause $join) {
                    $join->on('cards.id', '=', 't.card_id')
                        ->where('t.locale', '=', 'en');
                }
            )
            ->orderBy('t.kanji_count', 'asc')
            ->orderBy('id', 'asc')
            ->paginate(50);
        return $this->response->paginator($cards, $cardTransformer);
    }
}
