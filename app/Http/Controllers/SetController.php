<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Http\Controllers;

use amcsi\LyceeOverture\Card\SetTransformer;
use amcsi\LyceeOverture\Set;

class SetController extends Controller
{
    public function index(SetTransformer $setTransformer)
    {
        $cardSets = Set::orderBy('name_en')->get();
        return $this->response->collection($cardSets, $setTransformer);
    }
}
