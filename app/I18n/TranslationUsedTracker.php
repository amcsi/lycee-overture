<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n;

class TranslationUsedTracker
{
    private $translationsUsed = [];

    public function add($source)
    {
        $this->translationsUsed[] = $source;
    }

    public function get()
    {
        return $this->translationsUsed;
    }

    public function flush()
    {
        $this->translationsUsed = [];
    }
}
