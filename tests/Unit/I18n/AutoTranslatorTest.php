<?php
declare(strict_types=1);

namespace Tests\Unit\I18n;

use amcsi\LyceeOverture\I18n\AutoTranslator;
use PHPUnit\Framework\TestCase;

class AutoTranslatorTest extends TestCase
{
    /**
     * @dataProvider provideAutoTranslate
     */
    public function testAutoTranslate(string $expected, string $input)
    {
        self::assertSame($expected, AutoTranslator::autoTranslate($input));
    }

    public function provideAutoTranslate()
    {
        return [
            [
                '',
                '',
            ],
            [
                'Asdf',
                'asdf',
            ],
            [ // Punctuation.
                'する. する, する.',
                'する。する、する。',
            ],
            [ // Full width numbers.
                '0',
                '０',
            ],
            [ // Full width numbers.
                '9',
                '９',
            ],
            [ // Full width letters.
                'Z',
                'Ｚ',
            ],
            [
                '..とき this character gets AP+2, DP+2.',
                '..ときこのキャラにＡＰ＋２・ＤＰ＋２する。',
            ],
            [
                'This [花] character gets SP+1.',
                'この[花]キャラにＳＰ＋１する。',
            ],
            [
                '味方キャラが登場したとき, this character gets AP+1, DP+1.',
                '味方キャラが登場したとき、このキャラにＡＰ＋１・ＤＰ＋１する。',
            ],
            'gaining abilities' => [
                'This character gains [アグレッシブ].',
                'このキャラは[アグレッシブ]を得る。',
            ],
            'ally gaining abilities' => [
                '{Ally character} gains [オーダーステップ:[無]][サイドステップ:[無]].',
                '{味方キャラ1体}は[オーダーステップ:[無]][サイドステップ:[無]]を得る.',
            ],
            'ally getting stat changes' => [
                '{Ally character} gets SP+1.',
                '{味方キャラ1体}にSP+1する.',
            ],
            'enemy getting stat changes' => [
                '{Enemy character} gets SP-1.',
                '{相手キャラ1体}にSP-1する.',
            ],
            'enemy getting stat changes (no target)' => [
                'Enemy character gets DP-1.',
                '相手キャラ1体にDP-1する.',
            ],
            'compound target gets stat changes; separated target logic' => [
                '{Ally 「フルコンプ」 character} gets AP+1, DP+1.',
                '{味方「フルコンプ」キャラ１体}にＡＰ＋１・ＤＰ＋１する。',
            ],
            'target gets stat changes' => [
                '{Character} gets DMG-2.',
                '{キャラ1体}にDMG-2する.',
            ],
            'use during your turn' => [
                'Use during your turn.',
                '自ターン中に使用する. ',
            ],
            'When this character supports another character' => [
                'When this character supports another character.',
                'このキャラでサポートをしたとき. ',
            ],
            'When this character gets supported' => [
                'When this character gets supported.',
                'このキャラにサポートをしたとき. ',
            ],
            'do not use during battle' => [
                'Do not use during battle.',
                'バトル中に使用できない. ',
            ],
        ];
    }
}
