<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\NameTranslator;

use amcsi\LyceeOverture\I18n\TranslatorApi\YahooKanjiTranslator;
use JpnForPhp\Analyzer\Analyzer;

/**
 * Attempts to translate kanji names to English.
 *
 * Uses Yahoo's API, so this is slow to run through all the cards.
 */
class KanjiTranslator
{
    private $yahooKanjiTranslator;

    public function __construct(YahooKanjiTranslator $yahooKanjiTranslator)
    {
        $this->yahooKanjiTranslator = $yahooKanjiTranslator;
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
