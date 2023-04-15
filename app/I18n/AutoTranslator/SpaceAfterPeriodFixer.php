<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

/**
 * Ensures there's a space between sentences.
 */
class SpaceAfterPeriodFixer
{
    public static function fix(string $text): string
    {
        return preg_replace("/\\.(?![\s.]|$)/", '. ', $text);
    }
}
