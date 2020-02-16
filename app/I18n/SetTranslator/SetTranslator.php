<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\SetTranslator;

class SetTranslator
{
    private $search;
    private $replace;

    public function __construct(array $setTranslations)
    {
        $this->search = array_keys($setTranslations);
        $this->replace = array_values($setTranslations);
    }

    public function translate(string $text): string
    {
        return str_replace($this->search, $this->replace, $text);
    }
}
