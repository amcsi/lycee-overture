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
                'when an ally character enters engagement',
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
        ];
    }
}
