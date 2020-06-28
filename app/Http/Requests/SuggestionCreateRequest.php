<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Http\Requests;

use amcsi\LyceeOverture\I18n\Locale;
use amcsi\LyceeOverture\Rules\NoJapaneseCharactersRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SuggestionCreateRequest extends FormRequest
{
    public function authorize()
    {
        return \Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $lineRule = ['present', 'string', app(NoJapaneseCharactersRule::class)];

        return [
            'card_id' => 'required|exists:cards,id',
            'locale' => ['required', Rule::in(Locale::TRANSLATION_LOCALES)],
            'basic_abilities' => $lineRule,
            'pre_comments' => $lineRule,
            'ability_cost' => $lineRule,
            'ability_description' => $lineRule,
            'comments' => $lineRule,
        ];
    }
}
