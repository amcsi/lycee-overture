<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\DeeplTranslator;

readonly class DeeplMarkupWrapped
{
    public function __construct(public string $text, public array $parts)
    {
    }
}
