<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\NameTranslator;

/**
 * Attempts to translate kanji names to English.
 */
class KanjiTranslator
{
    public function __construct()
    {
    }

    public function translate(string $text): string
    {
        // Temporarily disable kanji translation until we find a replacement for the Yahoo API that does not allow
        // use outside of Japan anymore.
        return $text;
    }
}
