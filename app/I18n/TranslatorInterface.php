<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n;

interface TranslatorInterface
{
    public function translate(string $text): string;
}
