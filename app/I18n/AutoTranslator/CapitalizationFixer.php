<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\Card\AbilityType;
use amcsi\LyceeOverture\Card\Element;

class CapitalizationFixer
{
    public static function fixCapitalization(string $text): string
    {
        // We need to separate out the different rows of effects,
        // and disregard the [AbilityType] and "[Cost]: " parts for capitalization.
        // See tests.

        $abilityTypesRegex = '\[(?:' . implode('|', AbilityType::getJapaneseToMarkup()) . ')]';
        $pattern = "/($abilityTypesRegex(?: \[.*?:)?) (((?!$abilityTypesRegex).)*)/";

        // We don't want elements like [sun] to be capitalized.
        // TODO: possibly remove this once a refactor is made to not translate markup for Japanese locales in advance,
        // and potentially be able to translate markup later in the auto translate logic than fixCapitalization.
        $elementsRegex = implode('|', Element::getElementToMarkupMap());
        $notFollowedByElementAndClosingBracket = "(?!($elementsRegex)\])";

        return preg_replace_callback(
            "/((?:\\.\\s+|^\\s*|\\[){$notFollowedByElementAndClosingBracket}[a-z](?!ttp))/m",
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
