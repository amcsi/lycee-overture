<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\Locale;
use amcsi\LyceeOverture\I18n\OneSkyClient;

/**
 * Translates <quotes> "like these".
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

    public function autoTranslate(string $autoTranslated): string
    {
        $autoTranslated = preg_replace_callback(
            '/(?<=<)(.+?)(?=>)/',
            [$this, 'characterTypeCallback'],
            $autoTranslated
        );
        return preg_replace_callback(
            '/"(.+?)"/',
            [$this, 'nameCallback'],
            $autoTranslated
        );
    }

    public function characterTypeCallback(array $matches): string
    {
        return $this->tryToTranslateCharacterTypeExact($matches[0]);
    }

    public function tryToTranslateCharacterTypeExact(string $quoted): string
    {
        return $this->translations[Locale::ENGLISH]['translation'][OneSkyClient::CHARACTER_TYPES][$quoted] ?? $quoted;
    }

    public function nameCallback(array $matches): string
    {
        return '"' . $this->tryToTranslateNameExact($matches[1]) . '"';
    }

    public function tryToTranslateNameExact(string $quoted): string
    {
        return $this->translations[Locale::ENGLISH]['translation'][OneSkyClient::NAMES][$quoted] ??
            $this->translations[Locale::ENGLISH]['translation'][OneSkyClient::ABILITY_NAMES][$quoted] ??
            $quoted;
    }
}
