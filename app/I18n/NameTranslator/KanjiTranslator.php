<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\NameTranslator;

use amcsi\LyceeOverture\I18n\TranslatorInterface;
use JpnForPhp\Analyzer\Analyzer;

/**
 * Attempts to translate kanji names to English.
 */
class KanjiTranslator
{
    private $yahooKanjiTranslator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->yahooKanjiTranslator = $translator;
    }

    public function translate(string $text): string
    {
        if (strpos($text, '／') !== false) {
            // Perform the translation separately per slash separation.
            return implode('／', array_map([$this, 'translate'], explode('／', $text)));
        }

        // Only attempt to translate if the text has kanji, but does not have katakana or latin letters.
        $hasKanjiOrHiragana = Analyzer::hasKanji($text);
        if ($hasKanjiOrHiragana && !Analyzer::hasKatakana($text) && !Analyzer::hasLatinLetters($text)) {
            $text = $this->yahooKanjiTranslator->translate($text);
        }

        return $text;
    }
}
