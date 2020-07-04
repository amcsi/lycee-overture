<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Http\Controllers;

use amcsi\LyceeOverture\CardTranslation;
use amcsi\LyceeOverture\Http\Requests\SuggestionCreateRequest;
use amcsi\LyceeOverture\Http\Requests\SuggestionListRequest;
use amcsi\LyceeOverture\I18n\ManualTranslation\SuggestionResource;
use amcsi\LyceeOverture\Suggestion;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

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

        $suggestion = Suggestion::firstOrNew($attributes, $values);

        if ($request->approved) {
            $locale = $suggestion->locale;
            if (!in_array($locale, explode(',', \Auth::authenticate()->can_approve_locale), true)) {
                throw ValidationException::withMessages([
                    'approve' => ["You do not have the right to approve a translation in this language."],
                ]);
            }
            $newTranslationData = $suggestion->card->getTranslation("$locale-auto")->replicate()->toArray();

            // Grab all the auto-translated values, and merge in the card description related properties
            // that are in the translation suggestion.
            $tranlationValues = Arr::except($newTranslationData, $keyAttributes);
            $tranlationValues = array_replace(
                $tranlationValues,
                Arr::only($values, Suggestion::SUGGESTABLE_PROPERTIES)
            );
            CardTranslation::updateOrInsert($attributes, $tranlationValues);

            $suggestion->delete();
        } else {
            $suggestion->save();
        }
        $suggestion->load('creator');

        return new SuggestionResource($suggestion);
    }
}
