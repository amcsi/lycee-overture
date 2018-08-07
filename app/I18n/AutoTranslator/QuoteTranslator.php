<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\Locale;

/**
 * Translates <quote> "like these".
 */
class QuoteTranslator
{
    private $translations;

    /**
     * @param array $translations The translations in multilingual i18next format.
     */
    public function __construct(array $translations)
    {
        $this->translations = $translations;
    }

    public function autoTranslate(string $autoTranslated)
    {
        return preg_replace_callback('/(?<=<)(.+?)(?=>)/', [$this, 'callback'], $autoTranslated);
    }

    public function callback(array $matches): string
    {
        return $this->tryToTranslateExact($matches[0]);
    }

    public function tryToTranslateExact(string $quoted): string
    {
        return $this->translations[Locale::ENGLISH]['translation']['character_types'][$quoted] ?? $quoted;
    }


}
