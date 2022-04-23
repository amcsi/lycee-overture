<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Http\Controllers;

use amcsi\LyceeOverture\Card\DeckResource;
use amcsi\LyceeOverture\Card\DeckWithCardsResource;
use amcsi\LyceeOverture\Deck;

class DeckController extends Controller
{
    public function index()
    {
        $decks = Deck::orderBy('name_en')->get();
        return DeckResource::collection($decks);
    }

    public function show(Deck $deck)
    {
        return new DeckWithCardsResource($deck);
    }
}
