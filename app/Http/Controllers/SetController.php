<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Http\Controllers;

use amcsi\LyceeOverture\Card\SetResource;
use amcsi\LyceeOverture\Set;

class SetController extends Controller
{
    public function index()
    {
        $cardSets = Set::all();
        $locale = \App::getLocale();
        $cardSets = $cardSets->toBase()->sort(
            fn(Set $set1, Set $set2) => $set1->getFullName($locale) <=> $set2->getFullName($locale)
        );
        return SetResource::collection($cardSets);
    }
}
