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
        $text = preg_replace_callback('/\[([T雪月花宙日無]+)\]/u', ['self', 'elementCallback'], $text);

        $japaneseToMarkup = BasicAbility::getJapaneseToMarkup();
        $japaneseBasicAbilitiesRegex = implode('|', array_keys($japaneseToMarkup));

        $text = preg_replace_callback(
            "/\\[($japaneseBasicAbilitiesRegex)(?=[\\]:])/u",
            ['self', 'basicAbilityCallback'],
            $text
        );

        $abilityTypesRegex = implode('|', array_keys(AbilityType::getJapaneseMap()));

        $text = preg_replace_callback(
            "/\\[($abilityTypesRegex)/u",
            ['self', 'abilityTypeCallback'],
            $text
        );

        return $text;
    }

    private static function elementCallback(array $matches)
    {
        $elements = preg_split('//u', $matches[1], -1, PREG_SPLIT_NO_EMPTY);
        $markupMap = Element::getElementToMarkupMap();
        // Include 'T' for tap.
        $markupMap['T'] = 'T';
        foreach ($elements as $key => $value) {
            $elements[$key] = "[$markupMap[$value]]";
        }
        return implode('', $elements);
    }

    private static function basicAbilityCallback(array $matches)
    {
        $markupMap = BasicAbility::getJapaneseToMarkup();
        return '[' . $markupMap[$matches[1]];
    }

    private static function abilityTypeCallback(array $matches)
    {
        $markupMap = AbilityType::getJapaneseToMarkup();
        return '[' . $markupMap[$matches[1]];
    }
}
