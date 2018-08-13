<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Http\Controllers;

use amcsi\LyceeOverture\Card\CardSetTransformer;
use amcsi\LyceeOverture\CardSet;

class CardSetController extends Controller
{
    public function index(CardSetTransformer $cardSetTransformer)
    {
        return $this->response->collection(CardSet::all(), $cardSetTransformer);
    }
}
