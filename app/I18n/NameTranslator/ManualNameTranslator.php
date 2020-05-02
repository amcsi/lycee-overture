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
        return $this->getTranslation($quoted, [OneSkyClient::CHARACTER_TYPES]);
    }

    public function tryToTranslateNameExact(string $quoted): string
    {
        return $this->getTranslation($quoted, [OneSkyClient::NAMES, OneSkyClient::ABILITY_NAMES]);
    }

    /**
     * @param string $quoted The name/type to translate.
     * @param array $textTypes The order of which text types to look in for translations.
     * @return string
     */
    private function getTranslation(string $quoted, array $textTypes): string
    {
        return self::doSeparatedByPunctuation(
            $quoted,
            function ($part) use ($textTypes) {
                foreach ($textTypes as $textType) {
                    if (($translation = $this->translations[Locale::ENGLISH]['translation'][$textType][$part] ?? null)) {
                        return $translation;
                    }
                }

                return $part;
            }
        );
    }

    /**
     * Does an action on a string such that it first gets split by certain Japanese punctuation characters.
     * This is so that parts of translations could be reusable e.g. Saber／Arutoria Pendoragon.
     */
    public static function doSeparatedByPunctuation(string $input, callable $callable): string
    {
        return preg_replace_callback('/[^／・]+/u', fn(array $matches) => $callable($matches[0]), $input);
    }
}
