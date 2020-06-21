<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Http\Requests;

use amcsi\LyceeOverture\I18n\Locale;
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
        return [
            'card_id' => 'required|exists:cards,id',
            'locale' => ['required', Rule::in(Locale::TRANSLATION_LOCALES)],
            'basic_abilities' => 'present',
            'pre_comments' => 'present',
            'ability_cost' => 'present',
            'ability_description' => 'present',
            'comments' => 'present',
        ];
    }
}
