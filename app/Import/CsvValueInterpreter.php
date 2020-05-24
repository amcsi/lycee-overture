<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Import;

use amcsi\LyceeOverture\Card\AbilityType;
use amcsi\LyceeOverture\Card\Element;
use amcsi\LyceeOverture\Card\Type;
use Illuminate\Support\Str;

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
            if (Str::contains($elementsCell, $elementString)) {
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
        if (!preg_match('/^\[([^]]+)]/', $csvRow[CsvColumns::ABILITY], $matches)) {
            return 0;
        }
        $match = $matches[1];
        $map = AbilityType::getJapaneseMap();
        if (isset($map[$match])) {
            return $map[$match];
        }
        return 0;
    }

    /**
     * Extracts the different ability parts (the cost, description and comments) from a string ability.
     */
    public static function getAbilityPartsFromAbility(string $ability): array
    {

        $abilityRows = preg_split("/\s*\n\s*|\s*<br ?\/>\s*/i", $ability);

        $abilityTypeAndCostRegexPart = sprintf(
            "^(\[(?:%s)] )(?:(\[.*])+:)?",
            implode('|', array_keys(AbilityType::getJapaneseMap()))
        );

        // Contains types and costs. Each one is on the same line (\n) as the ability description it belongs to.
        $abilityTypeAndCosts = [];
        $abilityDescriptions = [];
        $comments = [];
        $preComments = [];

        foreach ($abilityRows as $abilityRow) {
            if (preg_match('/^(?:(?:装備制限:|\[装備制限])\s*)(.*)/u', $abilityRow, $matches)) {
                $preComments[] = "装備制限:$matches[1]";
                continue;
            }

            if (preg_match('/^(?:※|構築制限:|https?:\/\/)/u', $abilityRow)) {
                $comments[] = $abilityRow;
                continue;
            }

            if (preg_match("/$abilityTypeAndCostRegexPart(.*)/u", $abilityRow, $matches)) {
                $abilityTypeAndCosts[] = trim($matches[1] . $matches[2]);

                $description = trim($matches[3]);

                // Normalize description.
                $description = preg_replace(
                    sprintf(
                        '/%s(.*?)%s/',
                        preg_quote('<span style=color:#FFCC00;font-weight:bold;>', '/'),
                        preg_quote('</span>', '/')
                    ),
                    '{$1}',
                    $description
                );

                // Comments in description.
                preg_match('/^(.*?)(※.*)?$/u', $description, $_matches);
                $description = $_matches[1];
                $capturedComments = $_matches[2] ?? null;

                $abilityDescriptions[] = $description;

                if ($capturedComments) {
                    preg_match_all('/※.*/u', $capturedComments, $__matches, PREG_SET_ORDER);
                    /** @noinspection SlowArrayOperationsInLoopInspection */
                    $comments = array_merge($comments, $__matches[0]);
                }


                continue;
            }

            // Fall back to adding this row as a description.
            $abilityTypeAndCosts[] = '';
            $abilityDescriptions[] = $abilityRow;
        }

        return [
            'pre_comments' => implode("\n", $preComments),
            'ability_cost' => implode("\n", $abilityTypeAndCosts),
            'ability_description' => implode("\n", $abilityDescriptions),
            'comments' => implode("\n", $comments),
        ];
    }
}
