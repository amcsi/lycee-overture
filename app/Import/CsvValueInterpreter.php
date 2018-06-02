<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Import;

use amcsi\LyceeOverture\Card\AbilityType;
use amcsi\LyceeOverture\Card\Element;
use amcsi\LyceeOverture\Card\Type;

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
        $continuousPart = '';
        $abilityCost = '';
        $comments = '';
        $ability = preg_replace_callback('/^(\[[^\]]*\])\s*/', function ($matches) use (&$continuousPart) {
            // Maybe we'll want to later discard the whitespace by taking $matches[1].
            $continuousPart = $matches[0];
        }, $ability, 1);
        $ability = preg_replace_callback('/^(.*):/', function ($matches) use (&$abilityCost) {
            $abilityCost = $matches[1];
        }, $ability, 1);
        $ability = preg_replace_callback('/※.*$/', function ($matches) use (&$comments) {
            $comments = $matches[0];
        }, $ability, 1);
        $parts = [
            'ability_cost' => $abilityCost,
            'ability_description' => $continuousPart . $ability,
            'comments' => $comments,
        ];
        return $parts;
    }
}
