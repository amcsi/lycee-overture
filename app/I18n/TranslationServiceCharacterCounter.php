<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n;

/**
 * Attempted:   Texts attempted to be translated, except empty strings.
 * Passed:      Attempts not rejected due to not needing translating.
 * Sent:        Texts that were cache misses and needed a translation API call.
 */
class TranslationServiceCharacterCounter
{
    public int $translationsAttempted = 0;

    public int $translationsPassed = 0;

    public int $translationsSent = 0;

    public int $charactersAttempted = 0;

    public int $charactersPassed = 0;

    public int $charactersSent = 0;

    public function addCharactersAttempted(int $amount): void
    {
        $this->charactersAttempted += $amount;
        ++$this->translationsAttempted;
    }

    public function addCharactersPassed(int $amount): void
    {
        $this->charactersPassed += $amount;
        ++$this->translationsPassed;
    }

    public function addCharactersSent(int $amount): void
    {
        $this->charactersSent += $amount;
        ++$this->translationsSent;
    }
}
