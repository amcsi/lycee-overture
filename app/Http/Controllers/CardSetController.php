<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Http\Controllers;

use amcsi\LyceeOverture\Card\CardSetTransformer;
use amcsi\LyceeOverture\CardSet;

class CardSetController extends Controller
{
    public function index(CardSetTransformer $cardSetTransformer)
    {
        $cardSets = CardSet::orderBy('name_en')->get();
        return $this->response->collection($cardSets, $cardSetTransformer);
    }
}
