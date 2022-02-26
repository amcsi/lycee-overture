<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\TranslatorApi;

/**
 * Result of a translation from Yahoo's Japanese kanji decoder.
 */
class TranslationResult
{
    public function __construct(private array $words)
    {
    }

    public function getWords(): array
    {
        $return = [];

        foreach ($this->words as $word) {
            if (isset($word['roman'])) {
                $return[] = $word['roman'];
            }
        }

        return $return;
    }
}
