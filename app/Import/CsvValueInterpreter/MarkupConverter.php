<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Import\CsvValueInterpreter;

use amcsi\LyceeOverture\Card\AbilityType;
use amcsi\LyceeOverture\Card\BasicAbility;
use amcsi\LyceeOverture\Card\Element;

/**
 * Converts markup in Japanese (e.g. [アグレッシブ] and [日]), normalizes and translates it.
 */
class MarkupConverter
{
    public static function convert(string $text): string
    {
        $text = self::normalizeBrackets($text);
        $text = preg_replace_callback('/\[([雪月花宙日無])]/u', self::elementCallback(...), $text);

        $japaneseBasicAbilitiesRegex = self::getJapaneseBasicAbilitiesRegex();

        $text = preg_replace_callback(
            "/\\[($japaneseBasicAbilitiesRegex)(?=[\\]:])/u",
            self::basicAbilityCallback(...),
            $text
        );

        $abilityTypesRegex = self::getAbilityTypesRegex();

        $text = preg_replace_callback(
            "/$abilityTypesRegex/u",
            self::abilityTypeCallback(...),
            $text
        );

        return $text;
    }

    public static function normalizeBrackets($input)
    {
        return preg_replace_callback('/\[([TＴ雪月花宙日無]{2,})]/u', self::normalizeBracketsCallback(...), $input);
    }

    private static function normalizeBracketsCallback(array $matches)
    {
        return implode('', array_map(fn($char) => "[$char]", mb_str_split($matches[1])));
    }

    private static function elementCallback(array $matches)
    {
        return '[' . Element::getElementToMarkupMap()[$matches[1]] . ']';
    }

    private static function basicAbilityCallback(array $matches)
    {
        $markupMap = BasicAbility::getJapaneseToMarkup();
        return '[' . $markupMap[$matches[1]];
    }

    private static function abilityTypeCallback(array $matches)
    {
        $markupMap = AbilityType::getJapaneseToMarkup();
        return '[' . $markupMap[$matches[1]] . ']';
    }

    public static function getJapaneseBasicAbilitiesRegex(): string
    {
        $japaneseToMarkup = BasicAbility::getJapaneseToMarkup();
        return implode('|', array_keys($japaneseToMarkup));
    }

    public static function getAbilityTypesRegex(): string
    {
        $abilityTypesRegex = implode('|', array_keys(AbilityType::getJapaneseMap()));

        return "\\[($abilityTypesRegex)]";
    }
}
