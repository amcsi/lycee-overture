<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\NameTranslator;

use JpnForPhp\Analyzer\Analyzer;
use JpnForPhp\Transliterator\Transliterator;

class KanaTranslator
{
    private $transliterator;

    public function __construct(Transliterator $transliterator)
    {
        $this->transliterator = $transliterator;
    }

    public function translate(string $text): string
    {
        if (strpos($text, '／') !== false) {
            // Perform the translation separately per slash separation.
            return implode('／', array_map([$this, 'translate'], explode('／', $text)));
        }

        if (!Analyzer::hasKana($text)) {
            // No hiragana or katakana to translate.
            return $text;
        }

        if (Analyzer::hasKanji($text)) {
            // Avoid kanji.
            return $text;
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