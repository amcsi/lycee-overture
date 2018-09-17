<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\NameTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\QuoteTranslator;

class NameTranslator
{
    private $quoteTranslator;
    private $kanaTranslator;

    public function __construct(QuoteTranslator $quoteTranslator, KanaTranslator $kanaTranslator)
    {
        $this->quoteTranslator = $quoteTranslator;
        $this->kanaTranslator = $kanaTranslator;
    }

    /**
     * Tries to translate a card's name. First tries using the manual translations, then tries using the kana converter.
     */
    public function tryTranslate(string $untranslated): string
    {
        $translated = $this->quoteTranslator->tryToTranslateNameExact($untranslated);
        if ($translated !== $untranslated) {
            return $translated;
        }

        return $this->kanaTranslator->translate($untranslated);
    }
}