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
            'attacking with ally' => [
                'use while attacking with an ally character',
                '味方キャラの攻撃中に使用する',
            ],
            'during the game' => [
                'during the game',
                'ゲーム中',
            ],
            'do not use as response' => [
                "do not use as a response to your opponent's activation of an effect",
                '相手の宣言に対応して使用できない',
            ],
            'use as response to attack declaration' => [
                "use as a response to your opponent's attack declaration",
                '相手の攻撃宣言に対応して使用する',
            ],
            'does not wake up next wakeup' => [
                'in the next wake-up phase',
                '次のウェイクアップで',
            ],
            'does not wake up your next wakeup' => [
                'in your next wake-up phase',
                '次の自分のウェイクアップで',
            ],
            'does not wake up opponent next wakeup' => [
                "in your opponent's next wake-up phase",
                '次の相手のウェイクアップで',
            ],
            'at the end of battle when your deck was damaged' => [
                'at the end of battle when damage was dealt to your deck',
                '自分のデッキがダメージを受けたバトル終了時',
            ],
        ];
    }
}
