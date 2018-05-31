<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Import;

use amcsi\LyceeOverture\Card\Element;
use amcsi\LyceeOverture\Card\Type;
use PHPUnit\Framework\TestCase;

class CsvValueInterpreterTest extends TestCase
{
    /**
     * @dataProvider provideGetType
     */
    public function testGetType($expected, $inputCell): void
    {
        self::assertSame($expected, CsvValueInterpreter::getType([CsvColumns::TYPE => $inputCell]));
    }

    public function provideGetType(): array
    {
        return [
            [Type::CHARACTER, 'キャラクター'],
            [Type::ITEM, 'アイテム'],
            [Type::EVENT, 'イベント'],
        ];
    }

    /**
     * @dataProvider provideElements
     */
    public function testGetElements($expectedTrueElements, $elements): void
    {
        // All costs need to be present, so let's merge all-zeros with the non-zero expected costs.
        $expected = array_fill_keys(Element::getElementKeys(), false);
        foreach ($expectedTrueElements as $elementKey) {
            $expected[$elementKey] = true;
        }

        self::assertSame($expected, CsvValueInterpreter::getElements([CsvColumns::ELEMENTS => $elements]));
    }

    public function provideElements(): array
    {
        return [
            [[], '無'],
            [['snow'], '雪'],
            [['moon'], '月'],
            [['flower'], '花'],
            [['space'], '宙'],
            [['sun'], '日'],
            [['snow', 'moon', 'flower', 'space', 'sun'], '雪月花宙日'],
        ];
    }

    /**
     * @dataProvider provideCosts
     */
    public function testGetCosts($expectedNonZeroCosts, $costs): void
    {
        // All costs need to be present, so let's merge all-zeros with the non-zero expected costs.
        $expected = array_replace(array_fill_keys(Element::getCostKeys(), 0), $expectedNonZeroCosts);

        self::assertSame($expected, CsvValueInterpreter::getCosts([CsvColumns::COST => $costs]));
    }

    public function provideCosts(): array
    {
        return [
            [[], ''],
            [['cost_star' => 1], '無'],
            [['cost_snow' => 1], '雪'],
            [['cost_moon' => 1], '月'],
            [['cost_flower' => 1], '花'],
            [['cost_space' => 1], '宙'],
            [['cost_sun' => 1], '日'],
            [
                ['cost_snow' => 2, 'cost_moon' => 2, 'cost_flower' => 2, 'cost_space' => 2, 'cost_sun' => 2],
                '雪雪月月花花宙宙日日',
            ],
        ];
    }
}
