<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\Tools;

class QuoteFixer
{
    /**
     * Fixes quotes on all models.
     */
    public static function fixQuotesOnAll(bool $dryRun): array
    {
        return TextFixer::applyTextFixOnAll([self::class, 'fixQuotes'], $dryRun);
    }

    public static function fixQuotes(string $string): string
    {
        return preg_replace('/"(.*?)"/', '“$1”', $string);
    }
}
