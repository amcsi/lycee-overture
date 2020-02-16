<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Http\Controllers;

use amcsi\LyceeOverture\Card\SetTransformer;
use amcsi\LyceeOverture\Set;

class SetController extends Controller
{
    public function index(SetTransformer $setTransformer)
    {
        $cardSets = Set::all();
        $locale = \App::getLocale();
        $cardSets = $cardSets->toBase()->sort(
            fn(Set $set1, Set $set2) => $set1->getFullName($locale) <=> $set2->getFullName($locale)
        );
        return $this->response->collection($cardSets, $setTransformer);
    }
}
