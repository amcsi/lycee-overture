<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Http\Requests;

use amcsi\LyceeOverture\I18n\Locale;
use amcsi\LyceeOverture\Rules\NoJapaneseCharactersRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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
            'approved' => 'bool',
        ];
    }

    protected function passedValidation()
    {
        $locale = $this->request->get('locale');
        if ($this->approved && !in_array($locale, explode(',', $this->user()->can_approve_locale), true)) {
            throw new AccessDeniedHttpException();
        }
    }
}
