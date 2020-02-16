<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\Card\AbilityType;

class CapitalizationFixer
{
    public static function fixCapitalization(string $text): string
    {
        // We need to separate out the different rows of effects,
        // and disregard the [AbilityType] and "[Cost]: " parts for capitalization.
        // See tests.

        $abilityTypesRegex = '\[(?:' . implode('|', AbilityType::getJapaneseToMarkup()) . ')]';
        $pattern = "/($abilityTypesRegex(?: \[.*?:)?) (((?!$abilityTypesRegex).)*)/";
        return preg_replace_callback(
            '/(^[a-z]|(?:\.\s+)[a-z])(?!ttp)/',
            ['self', 'uppercaseCallback'],
            trim(preg_replace_callback($pattern, ['self', 'callback'], $text, PREG_SET_ORDER))
        );
    }

    private static function callback(array $matches): string
    {
        return $matches[1] . ' ' . ucfirst($matches[2]);
    }

    private static function uppercaseCallback(array $matches): string
    {
        return strtoupper($matches[1]);
    }
}
