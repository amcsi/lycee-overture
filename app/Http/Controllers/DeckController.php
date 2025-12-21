<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Http\Controllers;

use amcsi\LyceeOverture\Card\DeckResource;
use amcsi\LyceeOverture\Models\Deck;

class DeckController extends Controller
{
    public function index()
    {
        $decks = Deck::orderBy('name_en')->get();
        return DeckResource::collection($decks);
    }
}
