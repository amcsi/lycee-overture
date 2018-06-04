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

    /**
     * @dataProvider provideAbilities
     */
    public function testGetAbilityParts(array $expected, string $input): void
    {
        self::assertSame($expected, CsvValueInterpreter::getAbilityPartsFromAbility($input));
    }

    public function provideAbilities(): array
    {
        return [
            [
                [
                    'ability_cost' => '',
                    'ability_description' => '',
                    'comments' => '',
                ],
                '',
            ],
            [
                [
                    'ability_cost' => '',
                    'ability_description' => '-',
                    'comments' => '',
                ],
                '-',
            ],
            [
                [
                    'ability_cost' => '[T][このキャラを破棄する]',
                    'ability_description' => '{相手ＡＦキャラ１体}を手札に入れる。',
                    'comments' => '※このキャラは別番号の同名キャラとは別に４枚までデッキに入れることができる。',
                ],
                '[宣言] [T][このキャラを破棄する]:{相手ＡＦキャラ１体}を手札に入れる。※このキャラは別番号の同名キャラとは別に４枚までデッキに入れることができる。',
            ],
            'Two colons in ability' => [
                [
                    'ability_cost' => '[sun][sun]',
                    'ability_description' => '{味方キャラ１体}は[Step:[0]]を得る。',
                    'comments' => '',
                ],
                '[宣言] [日日]:{味方キャラ１体}は[ステップ:[0]]を得る。',
            ],
            'Non-cost colon' => [
                [
                    'ability_cost' => '',
                    'ability_description' => 'このキャラと同列の味方キャラ全ては[OrderChange:[0]]を得る。',
                    'comments' => '',
                ],
                '[常時] このキャラと同列の味方キャラ全ては[オーダーチェンジ:[0]]を得る。',
            ],
            'normalizing span to target' => [
                [
                    'ability_cost' => '[sun]',
                    'ability_description' => '{味方キャラ１体}にＡＰ＋１する。',
                    'comments' => '',
                ],
                '[宣言] [日]:<span style=color:#FFCC00;font-weight:bold;>味方キャラ１体</span>にＡＰ＋１する。',
            ],
        ];
    }
}
