<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\NameTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\FullWidthCharacters;
use amcsi\LyceeOverture\I18n\Locale;
use amcsi\LyceeOverture\I18n\OneSkyClient;

/**
 * For translating names based off of manual translations.
 */
class ManualNameTranslator
{
    private $translations;

    /**
     * @param array $translations The translations in multilingual i18next format.
     */
    public function __construct(array $translations)
    {
        foreach (OneSkyClient::getTranslationGroupKeys() as $groupKey) {
            if (isset($translations[Locale::ENGLISH]['translation'][$groupKey])) {
                $localTranslations =& $translations[Locale::ENGLISH]['translation'][$groupKey];
                foreach ($localTranslations as $key => $value) {
                    // Have space-stripped copies of the string as source texts.
                    // This is because some name references in card descriptions have no space, where the named card does.
                    $localTranslations[str_replace(' ', '', $key)] = $value;
                    // Also translate full width characters in case the card text references card names with full width chars.
                    $localTranslations[FullWidthCharacters::translateFullWidthCharacters($key)] = $value;
                }
            }
        }
        $this->translations = $translations;
    }

    public function tryToTranslateCharacterTypeExact(string $quoted): string
    {
        return $this->translations[Locale::ENGLISH]['translation'][OneSkyClient::CHARACTER_TYPES][$quoted] ?? $quoted;
    }

    public function tryToTranslateNameExact(string $quoted): string
    {
        return $this->translations[Locale::ENGLISH]['translation'][OneSkyClient::NAMES][$quoted] ??
            $this->translations[Locale::ENGLISH]['translation'][OneSkyClient::ABILITY_NAMES][$quoted] ??
            $quoted;
    }

    /**
     * Does an action on a string such that it first gets split by certain Japanese punctuation characters.
     * This is so that parts of translations could be reusable e.g. Saber／Arutoria Pendoragon.
     */
    public static function doSeparatedByPunctuation(string $input, callable $callable): array
    {
        return array_map(fn(string $part) => $callable($part), preg_split('/[／・]/u', $input));
    }
}
