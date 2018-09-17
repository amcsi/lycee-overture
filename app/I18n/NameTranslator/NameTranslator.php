<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\NameTranslator;

class NameTranslator
{
    private $manualNameTranslator;
    private $kanaTranslator;

    public function __construct(ManualNameTranslator $manualNameTranslator, KanaTranslator $kanaTranslator)
    {
        $this->manualNameTranslator = $manualNameTranslator;
        $this->kanaTranslator = $kanaTranslator;
    }

    /**
     * Tries to translate a card's name. First tries using the manual translations, then tries using the kana converter.
     */
    public function tryTranslateName(string $untranslated): string
    {
        $translated = $this->manualNameTranslator->tryToTranslateNameExact($untranslated);
        if ($translated !== $untranslated) {
            return $translated;
        }

        return $this->kanaTranslator->translate($untranslated);
    }

    /**
     * Tries to translate a card's name. First tries using the manual translations, then tries using the kana converter.
     */
    public function tryTranslateCharacterType(string $untranslated): string
    {
        return $this->manualNameTranslator->tryToTranslateCharacterTypeExact($untranslated);
    }
}