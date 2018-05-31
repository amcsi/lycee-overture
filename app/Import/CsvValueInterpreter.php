<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Import;

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
}
