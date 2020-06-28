<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Http\Controllers;

use amcsi\LyceeOverture\Http\Requests\SuggestionCreateRequest;
use amcsi\LyceeOverture\Http\Requests\SuggestionListRequest;
use amcsi\LyceeOverture\I18n\ManualTranslation\SuggestionResource;
use amcsi\LyceeOverture\Suggestion;

class SuggestionController
{
    public function index(SuggestionListRequest $request)
    {
        $query = Suggestion::query();
        if ($request['card_id']) {
            $query->whereCardId($request['card_id']);
        }
        if ($request['locale']) {
            $query->whereLocale($request['locale']);
        }
        $query->with('creator');
        $query->orderBy('id', 'desc');

        return SuggestionResource::collection($query->get());
    }

    public function store(SuggestionCreateRequest $request)
    {
        $keyAttributes = ['card_id', 'locale'];
        $attributes = [];
        $values = [];
        foreach ($request->validated() as $key => $value) {
            if (in_array($key, $keyAttributes, true)) {
                $attributes[$key] = $value;
            } else {
                $values[$key] = $value;
            }
        }

        return new SuggestionResource(Suggestion::updateOrCreate($attributes, $values));
    }
}
