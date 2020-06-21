<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Http\Controllers;

use amcsi\LyceeOverture\Card\CardSetResource;
use amcsi\LyceeOverture\CardSet;

class CardSetController extends Controller
{
    public function index()
    {
        $cardSets = CardSet::orderBy('name_en')->get();
        return CardSetResource::collection($cardSets);
    }
}
