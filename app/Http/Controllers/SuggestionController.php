<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Http\Controllers;

use amcsi\LyceeOverture\Http\Requests\SuggestionCreateRequest;
use amcsi\LyceeOverture\I18n\ManualTranslation\SuggestionResource;
use amcsi\LyceeOverture\Suggestion;

class SuggestionController
{
    public function store(SuggestionCreateRequest $request)
    {
        return new SuggestionResource(Suggestion::create($request->validated()));
    }
}
