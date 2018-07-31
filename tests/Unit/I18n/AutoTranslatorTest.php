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
                'This [sun] character gets AP+1, DP-1.',
                'この[sun]キャラにＡＰ＋１・ＤＰ－１する。',
            ],
            [
                'When an ally character is summoned, this character gets AP+1, DP+1.',
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
            'gaining multiple abilities' => [
                'This character gains [Aggressive][OrderStep:[0]].',
                'このキャラは[Aggressive][OrderStep:[0]]を得る。',
            ],
            'ally gaining abilities' => [
                '{1 ally character} gains [オーダーステップ:[無]][サイドステップ:[無]].',
                '{味方キャラ1体}は[オーダーステップ:[無]][サイドステップ:[無]]を得る.',
            ],
            'ally getting stat changes' => [
                '{1 ally character} gets SP+1.',
                '{味方キャラ1体}にSP+1する.',
            ],
            'enemy getting stat changes' => [
                '{1 enemy character} gets SP-1.',
                '{相手キャラ1体}にSP-1する.',
            ],
            'enemy getting stat changes (no target)' => [
                '1 enemy character gets DP-1.',
                '相手キャラ1体にDP-1する.',
            ],
            'compound target gets stat changes; separated target logic' => [
                '{1 「フルコンプ」 ally character} gets AP+1, DP+1.',
                '{味方「フルコンプ」キャラ１体}にＡＰ＋１・ＤＰ＋１する。',
            ],
            'target gets stat changes' => [
                '{1 character} gets DMG-2.',
                '{キャラ1体}にDMG-2する.',
            ],
            'ー character' => [
                '{1 character} gets DMG-2.',
                '{キャラ1体}にDMGー2する.',
            ],
            '− character' => [
                '{1 character} gets DMG-2.',
                '{キャラ1体}にDMG−2する.',
            ],
            'multiple subjects in subsentence for stat changes' => [
                'このキャラを除く all your characters get AP+1, DP+1, DMG+1.',
                'このキャラを除く味方キャラ全てにＡＰ＋１・ＤＰ＋１・ＤＭＧ＋１する。',
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
            'opponent turn start' => [
                "At the start of your opponent's turn",
                '相手ターン開始時',
            ],
            'two stat changes' => [
                '{1 untapped AF ally character} gets DMG-1. This character gets DMG+1.',
                '{未行動の味方ＡＦキャラ１体}にＤＭＧ－１する。このキャラにＤＭＧ＋１する。',
            ],
            'when opponent destroyed by your effect' => [
                'When an opponent character is destroyed by use of your effects',
                '自分の効果によって相手キャラを破棄したとき',
            ],
            'when destroyed from engage summon' => [
                'When this character is destroyed due to Engage summon',
                'このキャラをエンゲージ登場によって破棄したとき',
            ],
            'this effect is lost' => [
                'This effect can be used only once while this card is on the field.',
                'この能力は失われる。',
            ],
            'negate its effect' => [
                'Negate its effect.',
                'その宣言の解決は失敗する。',
            ],
            'At the end of the battle when the other character goes down' => [
                'At the end of the battle when the other character is defeated.',
                '相手キャラがダウンしたバトル終了時。',
            ],
            'move' => [
                'Move {a character} to {an ally field}.',
                '{キャラ}を{味方フィールド}に移動する。',
            ],
            'resources getting provided' => [
                'Use during your opponent\'s turn. Discard 2 cards from the top of your deck. When destroyed, you get [moon][moon].',
                '相手ターン中に使用する。自分のデッキを２枚破棄する。破棄したとき、[moon][moon]を発生する。',
            ],
            'when your opponent activates an ability' => [
                'Use when your opponent activates an ability.',
                '相手の能力の宣言に対応して使用する。',
            ],
        ];
    }
}
