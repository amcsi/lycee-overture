<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\NameTranslator;

use amcsi\LyceeOverture\I18n\OneSkyClient;

class NameTranslator
{
    private $manualNameTranslator;
    private $kanaTranslator;

    private static $punctuationTranslationMap = [
        '／' => ' / ',
        '・' => ', ',
    ];

    private $punctuationSearch;
    private $punctuationReplace;

    public function __construct(ManualNameTranslator $manualNameTranslator, KanaTranslator $kanaTranslator)
    {
        $this->manualNameTranslator = $manualNameTranslator;
        $this->kanaTranslator = $kanaTranslator;
        $this->punctuationSearch = array_keys(self::$punctuationTranslationMap);
        $this->punctuationReplace = array_values(self::$punctuationTranslationMap);
    }

    /**
     * Tries to translate a card's name. First tries using the manual translations, then tries using the kana converter.
     */
    public function tryTranslateName(string $untranslated): string
    {
        return $this->tryTranslate($untranslated, [OneSkyClient::NAMES, OneSkyClient::ABILITY_NAMES]);
    }

    /**
     * Tries to translate a card's name. First tries using the manual translations, then tries using the kana converter.
     */
    public function tryTranslateCharacterType(string $untranslated): string
    {
        return $this->tryTranslate($untranslated, [OneSkyClient::CHARACTER_TYPES]);
    }

    /**
     * Tries to translate a card's name/type. First tries using the manual translations, then tries using the kana
     * converter.
     */
    private function tryTranslate(string $untranslated, $textTypes): string
    {
        $translated = $this->manualNameTranslator->tryToTranslate($untranslated, $textTypes);
        if ($translated === $untranslated) {
            $translated = $this->kanaTranslator->translate($untranslated);
        }

        // Romanize some punctuation.
        return str_replace($this->punctuationSearch, $this->punctuationReplace, $translated);
    }
}
