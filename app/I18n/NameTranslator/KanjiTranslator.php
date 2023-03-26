<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\NameTranslator;

use amcsi\LyceeOverture\I18n\Locale;
use amcsi\LyceeOverture\I18n\TranslatorInterface;

/**
 * Attempts to translate kanji names to English.
 */
class KanjiTranslator
{
    public function __construct(private readonly TranslatorInterface $cachedDeeplTranslator)
    {
    }

    public function translate(string $text): string
    {
        return $this->cachedDeeplTranslator->translate($text, Locale::ENGLISH);
    }
}
