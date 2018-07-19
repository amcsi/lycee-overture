<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Import;

use amcsi\LyceeOverture\Card\AbilityType;
use amcsi\LyceeOverture\Card\Element;
use amcsi\LyceeOverture\Card\Type;
use amcsi\LyceeOverture\Import\CsvValueInterpreter\MarkupConverter;

/**
 * Interprets values from csv rows.
 */
class CsvValueInterpreter
{
    public static function getType(array $csvRow): int
    {
        $cellValue = $csvRow[CsvColumns::TYPE];
        switch ($cellValue) {
            case 'キャラクター':
                return Type::CHARACTER;
            case 'アイテム':
                return Type::ITEM;
            case 'イベント':
                return Type::EVENT;
            default:
                throw new \InvalidArgumentException("Unknown Type: $cellValue");
        }
    }

    public static function getElements(array $csvRow): array
    {
        $return = array_fill_keys(Element::getElementKeys(), false);
        $elementKeys = Element::getElementKeys();
        $elementsCell = $csvRow[CsvColumns::ELEMENTS];
        foreach (Element::getColoredElementMap() as $elementString => $elementId) {
            if (str_contains($elementsCell, $elementString)) {
                $return[$elementKeys[$elementId]] = true;
            }
        }
        return $return;
    }

    public static function getCosts(array $csvRow): array
    {
        $return = [];
        $costKeys = Element::getCostKeys();
        $costCell = $csvRow[CsvColumns::COST];
        foreach ($costKeys as $elementId => $costKey) {
            $return[$costKey] = 0;
        }
        foreach (Element::$elementMap as $elementString => $elementId) {
            $return[$costKeys[$elementId]] += substr_count($costCell, $elementString);
        }
        return $return;
    }

    public static function getAbilityType(array $csvRow): int
    {
        if (!preg_match('/^\[([^]]+)\]/', $csvRow[CsvColumns::ABILITY], $matches)) {
            return 0;
        }
        $match = $matches[1];
        $map = AbilityType::getJapaneseMap();
        if (isset($map[$match])) {
            return $map[$match];
        }
        throw new \InvalidArgumentException("Unknown ability type: $match");
    }

    /**
     * Extracts the different ability parts (the cost, description and comments) from a string ability.
     */
    public static function getAbilityPartsFromAbility(string $ability): array
    {
        $abilityCost = '';
        $comments = '';

        $abilityTypesRegex = '\[(' . implode('|', array_keys(AbilityType::getJapaneseMap())) . ')]';
        $abilityJapaneseToMarkupMap = AbilityType::getJapaneseToMarkup();
        // Split by effects
        $pattern = "/$abilityTypesRegex(?:(?!$abilityTypesRegex).)*/";
        preg_match_all($pattern, $ability, $matches, PREG_SET_ORDER);

        if (!$matches) {
            // If there were no matches for different ability types, then either there's no ability, or this is not
            // a character. So just use the entire text as the abilities.
            $matches = [[$ability]];
        }

        $abilities = '';
        foreach ($matches as [$ability]) {
            $ability = MarkupConverter::convert($ability);

            $ability = preg_replace_callback(
                "/^$abilityTypesRegex/",
                function (array $matches) use ($abilityJapaneseToMarkupMap) {
                    return '[' . $abilityJapaneseToMarkupMap[$matches[1]] . ']';
            }, $ability);

            $ability = preg_replace_callback('/^((?:\[[^\]]+\])+):/u', function ($matches) use (&$abilityCost) {
                $abilityCost = $matches[1];
            }, $ability, 1);
            // Comments
            $ability = preg_replace_callback('/※.*$/', function ($matches) use (&$comments) {
                $comments .= $matches[0];
            }, $ability, 1);
            $ability = str_ireplace('<br />', "\n", $ability);
            // Deck restriction comments.
            $ability = preg_replace_callback(
                '/\n(構築制限:.*)$/i',
                function ($matches) use (&$comments) {
                    $comments .= $matches[1];
                },
                $ability,
                1
            );

            // Normalize description.
            $ability = preg_replace(
                sprintf(
                    '/%s(.*?)%s/',
                    preg_quote('<span style=color:#FFCC00;font-weight:bold;>', '/'),
                    preg_quote('</span>', '/')
                ),
                '{$1}',
                $ability
            );

            $abilities .= $ability;
        }

        $parts = [
            'ability_description' => $abilities,
            'comments' => $comments,
        ];

        return $parts;
    }
}
