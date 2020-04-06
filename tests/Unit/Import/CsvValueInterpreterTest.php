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
                    'ability_cost' => '[Activate] [T][このキャラを破棄する]',
                    'ability_description' => '{相手ＡＦキャラ１体}を手札に入れる。',
                    'comments' => '※このキャラは別番号の同名キャラとは別に４枚までデッキに入れることができる。',
                ],
                '[宣言] [T][このキャラを破棄する]:{相手ＡＦキャラ１体}を手札に入れる。※このキャラは別番号の同名キャラとは別に４枚までデッキに入れることができる。',
            ],
            'Two colons in ability' => [
                [
                    'ability_cost' => '[Activate] [sun][sun]',
                    'ability_description' => '{味方キャラ１体}は[Step:[0]]を得る。',
                    'comments' => '',
                ],
                '[宣言] [日日]:{味方キャラ１体}は[ステップ:[0]]を得る。',
            ],
            'tap included' => [
                [
                    'ability_cost' => '[Activate] [T][sun][sun]',
                    'ability_description' => '{味方キャラ１体}は...',
                    'comments' => '',
                ],
                '[宣言] [T日日]:{味方キャラ１体}は...',
            ],
            'Non-cost colon' => [
                [
                    'ability_cost' => '[Continuous]',
                    'ability_description' => 'このキャラと同列の味方キャラ全ては[Order Change:[0]]を得る。',
                    'comments' => '',
                ],
                '[常時] このキャラと同列の味方キャラ全ては[オーダーチェンジ:[0]]を得る。',
            ],
            'normalizing span to target' => [
                [
                    'ability_cost' => '[Activate] [sun]',
                    'ability_description' => '{味方キャラ１体}にＡＰ＋１する。',
                    'comments' => '',
                ],
                '[宣言] [日]:<span style=color:#FFCC00;font-weight:bold;>味方キャラ１体</span>にＡＰ＋１する。',
            ],
            '<br /> comments' => [
                [
                    'ability_cost' => '[Trigger]',
                    'ability_description' => 'このキャラにサポートをしたとき、このキャラを未行動にする。',
                    'comments' => '構築制限:ゆずソフト,ゆずソフト 1.0,へいろー',
                ],
                '[誘発] このキャラにサポートをしたとき、このキャラを未行動にする。<BR />構築制限:ゆずソフト,ゆずソフト 1.0,へいろー',
            ],
            'two effects' => [
                [
                    'ability_cost' => "[Continuous]\n[Activate] [star][star][star]",
                    'ability_description' => 'このキャラにＤＭＧ－２する。' . "\n" .
                        '相手ターン中に使用する。このアイテムを除外する。',
                    'comments' => '',
                ],
                '[常時] このキャラにＤＭＧ－２する。<br />[宣言] [無無無]:相手ターン中に使用する。このアイテムを除外する。',
            ],
            'equip restriction' => [
                [
                    'ability_cost' => '[Equip Restriction]',
                    'ability_description' => 'Something.',
                    'comments' => '',
                ],
                '[装備制限] Something.',
            ],
            'equip restriction alternative form' => [
                [
                    'ability_cost' => "[Equip Restriction]\n[Continuous]",
                    'ability_description' => "[sun]キャラ\nこのキャラにＡＰ＋１・ＤＰ－１する。",
                    'comments' => '',
                ],
                '装備制限:[日]キャラ<br />[常時] このキャラにＡＰ＋１・ＤＰ－１する。',
            ],
            'congratulations' => [
                [
                    'ability_cost' => '',
                    'ability_description' => 'Congratulations!!',
                    'comments' => "※このカードは「ラッキーカードキャンペーン」の当たりカードです。キャンペーンの詳細は以下をご確認ください。\nhttps://lycee-tcg.com/lucky/\n※このカードは能力を持たないキャラとしてゲームで使用できます。",
                ],
                'Congratulations!! <br />※このカードは「ラッキーカードキャンペーン」の当たりカードです。キャンペーンの詳細は以下をご確認ください。 <br />https://lycee-tcg.com/lucky/ <br />※このカードは能力を持たないキャラとしてゲームで使用できます。',
            ],
            'accommodate new CSV format 2020-04' => [
                [
                    'ability_cost' => '',
                    'ability_description' => '[相手キャラ１体]にＳＰ－２する。',
                    'comments' => '',
                ],
                '[相手キャラ１体]にＳＰ－２する。',
            ],
            'event text with markup' => [
                [
                    'ability_cost' => '',
                    'ability_description' => 'Bla bla bla [sun]',
                    'comments' => '',
                ],
                'Bla bla bla [日]',
            ],
        ];
    }
}
