<?php
declare(strict_types=1);

namespace Tests\Unit\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\TurnAndBattle;
use PHPUnit\Framework\TestCase;

class TurnAndBattleTest extends TestCase
{
    /**
     * @dataProvider provideAutoTranslate
     */
    public function testAutoTranslate(string $expected, string $input)
    {
        self::assertSame($expected, TurnAndBattle::autoTranslate($input));
    }

    public function provideAutoTranslate()
    {
        return [
            [
                'use during your turn',
                '自ターン中に使用する',
            ],
            [
                'use during your opponent\'s turn',
                '相手ターン中に使用する',
            ],
            [
                'during this turn',
                'このターン中',
            ],
            [
                'at the start of your opponent\'s turn',
                '相手ターン開始時',
            ],
            [
                'at the start of your turn',
                '自ターン開始時',
            ],
            [
                'at the start of your next turn',
                '次の自ターン開始時',
            ],
            [
                'until the end of your turn',
                '自ターン終了時まで',
            ],
            [
                'during your turn',
                '自ターン中',
            ],
            [
                'until the end of the turn',
                'ターン終了時まで',
            ],
            'imaginary case with do not use' => [
                'do not use during your turn',
                '自ターン中に使用できない',
            ],
            "at the end of this character's battle" => [
                "at the end of this character's battle",
                'このキャラのバトル終了時',
            ],
            'when attacking' => [
                'use while attacking with this character',
                'このキャラの攻撃中に使用する',
            ],
            'when defending' => [
                'use while defending with this character',
                'このキャラの防御中に使用する',
            ],
            'end of turn ally char down' => [
                'at the end of battle when an ally character is defeated',
                '味方キャラがダウンしたバトル終了時',
            ],
        ];
    }
}
