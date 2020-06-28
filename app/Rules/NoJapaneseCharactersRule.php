<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Rules;

use amcsi\LyceeOverture\I18n\JapaneseCharacterCounter;
use Illuminate\Contracts\Validation\Rule;

class NoJapaneseCharactersRule implements Rule
{
    public function passes($attribute, $value)
    {
        return JapaneseCharacterCounter::countJapaneseCharacters($value) === 0;
    }

    public function message()
    {
        return trans('validation.no_japanese_characters');
    }
}
