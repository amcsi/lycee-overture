<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\NameTranslator;

use JpnForPhp\Analyzer\Analyzer;
use JpnForPhp\Transliterator\Transliterator;

/**
 * Translates hiragana and katakana to romaji.
 */
class KanaTranslator
{
    public function __construct(private Transliterator $transliterator)
    {
    }

    public function translate(string $text): string
    {
        if (!Analyzer::hasKana($text)) {
            // No hiragana or katakana to translate.
            return $text;
        }

        if (Analyzer::hasKanji($text)) {
            // Avoid kanji.
            return $text;
        }

        if (Analyzer::hasLatinLetters($text)) {
            $pattern = '/\p{Latin}+/u';
            preg_match($pattern, $text, $matches);
            $split = preg_split($pattern, $text, 2);

            // Translate the words around the latin letters.
            return $this->translate($split[0]) . ' ' . trim($matches[0]) . ' ' . $this->translate($split[1]);
        }

        $translated = ucfirst($this->transliterator->transliterate($text));

        return preg_replace_callback('/-(.)/', [$this, 'dashSpaceCallback'], $translated);
    }

    /**
     * JpnForPhp translates middle dots as dashes whereas I want them as spaces.
     * Also, capitalizes the first letter after the space, assuming it's a component of the name.
     */
    private function dashSpaceCallback(array $matches): string
    {
        return ' ' . strtoupper($matches[1]);
    }
}
