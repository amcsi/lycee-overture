<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n;

/**
 * Just returns the same source text.
 */
class NullTranslator implements TranslatorInterface
{
    public function translate(string $text, string $locale): string
    {
        return $text;
    }
}
