<?php
declare(strict_types=1);

namespace Tests\Unit\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\WhenSomething;
use PHPUnit\Framework\TestCase;

class WhenSomethingTest extends TestCase
{
    /**
     * @dataProvider provideAutoTranslate
     */
    public function testAutoTranslate(string $expected, string $input)
    {
        self::assertSame($expected, WhenSomething::autoTranslate($input));
    }

    public function provideAutoTranslate()
    {
        return [
            [
                'when an ally character is Engage summoned',
                '味方キャラがエンゲージ登場したとき',
            ],
            [
                'when this character moves',
                'このキャラが移動したとき',
            ],
            [
                'when you declare an attack with this character',
                'このキャラで攻撃宣言をしたとき',
            ],
            [
                'when this character is defeated in battle',
                'このキャラがダウンしたとき',
            ],
            [
                'when tapped',
                '行動済みにしたとき',
            ],
            [
                'when destroyed',
                '破棄したとき',
            ],
            [
                'when an ally character gets engaged',
                '味方キャラがエンゲージ登場している場合',
            ],
            'when destroyed (generic over subjects)' => [
                'when an ally character gets destroyed',
                '味方キャラを破棄したとき',
            ],
            'when moves (generic over subjects)' => [
                'when an ally character moves',
                '味方キャラが移動したとき',
            ],
            'when stats' => [
                "when this character's SP is 5 or more",
                'このキャラのSPが5以上の場合',
            ],
            'when this character is targeted by one of your effects' => [
                'when this character is targeted by one of your effects',
                'このキャラを自分の能力の対象に指定したとき',
            ],
            'when this character is discarded from the deck' => [
                'when this character is discarded from the deck',
                'このキャラが自分のデッキから破棄されたとき',
            ],
        ];
    }
}
