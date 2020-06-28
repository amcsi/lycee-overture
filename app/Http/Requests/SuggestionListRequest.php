<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Http\Requests;

use amcsi\LyceeOverture\I18n\Locale;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SuggestionListRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'card_id' => 'nullable',
            'locale' => Rule::in(Locale::TRANSLATION_LOCALES),
        ];
    }
}
