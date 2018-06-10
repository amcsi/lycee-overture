<?php

namespace amcsi\LyceeOverture\Http\Controllers;

use amcsi\LyceeOverture\Card;
use amcsi\LyceeOverture\Card\CardTransformer;

class CardController extends Controller
{
    public function index(CardTransformer $cardTransformer)
    {
        $cards = Card::paginate(50);
        return $this->response->paginator($cards, $cardTransformer);
    }
}
