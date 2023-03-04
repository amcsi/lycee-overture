<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n;

class TranslationUsedTracker
{
    private array $translationsUsed;
    private TranslationServiceCharacterCounter $characterCounter;

    public function __construct()
    {
        $this->flush();
    }

    public function flush()
    {
        $this->translationsUsed = [];
        $this->characterCounter = new TranslationServiceCharacterCounter();
    }

    public function add($source)
    {
        $this->translationsUsed[] = $source;
    }

    public function get()
    {
        return $this->translationsUsed;
    }

    public function getCharacterCounter(): TranslationServiceCharacterCounter
    {
        return $this->characterCounter;
    }
}
