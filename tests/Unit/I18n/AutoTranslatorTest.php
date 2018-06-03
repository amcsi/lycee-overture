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
            'all your characters get stats' => [
                'All your characters get AP+1, DP+1, DMG+1.',
                '味方キャラ全てにAP+1, DP+1, DMG+1する。',
            ],
            'all your [sun] characters get stats' => [
                'All your [sun] characters get AP+1, DP+1, DMG+1.',
                '味方[sun]キャラ全てにAP+1, DP+1, DMG+1する。',
            ],
            'all enemy characters get stats' => [
                'All enemy characters get AP+1, DP+1, DMG+1.',
                '相手キャラ全てにAP+1, DP+1, DMG+1する。',
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
            'use during your opponent\'s turn' => [
                'Use during your opponent\'s turn.',
                '相手ターン中に使用する. ',
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
            'use during battle involving this character' => [
                'Use during battle involving this character.',
                'このキャラのバトル中に使用する. ',
            ],
            'use during battle' => [
                'Use during battle.',
                'バトル中に使用する. ',
            ],
            'during this turn' => [
                'During this turn.',
                'このターン中. ',
            ],
            'fix capitalization' => [
                'Use during your turn. This character gets AP+3.',
                'Use during your turn. this character gets AP+3.',
            ],
        ];
    }
}
