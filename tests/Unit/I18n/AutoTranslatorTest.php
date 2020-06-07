<?php
declare(strict_types=1);

namespace Tests\Unit\I18n;

use PHPUnit\Framework\TestCase;
use Tests\Tools\TestUtils;

class AutoTranslatorTest extends TestCase
{
    /**
     * @dataProvider provideAutoTranslate
     */
    public function testAutoTranslate(string $expected, string $input)
    {
        self::assertSame($expected, TestUtils::createAutoTranslator()->autoTranslate($input));
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
                'This [flower] character gets SP+1.',
                'この[花]キャラにＳＰ＋１する。',
            ],
            [
                'This [sun] character gets AP+1, DP-1.',
                'この[日]キャラにＡＰ＋１・ＤＰ－１する。',
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
                '味方[日]キャラ全てにAP+1, DP+1, DMG+1する。',
            ],
            'all enemy characters get stats' => [
                'All enemy characters get AP+1, DP+1, DMG+1.',
                '相手キャラ全てにAP+1, DP+1, DMG+1する。',
            ],
            'gaining abilities' => [
                'This character gains [Aggressive].',
                'このキャラは[アグレッシブ]を得る。',
            ],
            'gaining multiple abilities' => [
                'This character gains [Aggressive][Order Step:[0]].',
                'このキャラは[アグレッシブ][オーダーステップ:[0]]を得る。',
            ],
            'ally gaining abilities' => [
                '{1 ally character} gains [Order Step:[star]][Side Step:[star]].',
                '[味方キャラ1体]は[オーダーステップ:[無]][サイドステップ:[無]]を得る.',
            ],
            'opponent losing abilities' => [
                '{1 enemy character} loses [Aggressive] until the end of the turn.',
                '[相手キャラ１体]はターン終了時まで[アグレッシブ]を失う。',
            ],
            'ally getting stat changes' => [
                '{1 ally character} gets SP+1.',
                '[味方キャラ1体]にSP+1する.',
            ],
            'enemy getting stat changes' => [
                '{1 enemy character} gets SP-1.',
                '[相手キャラ1体]にSP-1する.',
            ],
            'enemy getting stat changes (no target)' => [
                '1 enemy character gets DP-1.',
                '相手キャラ1体にDP-1する.',
            ],
            'compound target gets stat changes; separated target logic' => [
                '{1 ally "Furukonpu" character} gets AP+1, DP+1.',
                '[味方「フルコンプ」キャラ１体]にＡＰ＋１・ＤＰ＋１する。',
            ],
            'target gets stat changes' => [
                '{1 character} gets DMG-2.',
                '[キャラ1体]にDMG-2する.',
            ],
            'ー character' => [
                '{1 character} gets DMG-2.',
                '[キャラ1体]にDMGー2する.',
            ],
            '− character' => [
                '{1 character} gets DMG-2.',
                '[キャラ1体]にDMG−2する.',
            ],
            'subject appearing as square brackets' => [
                '{1 character} gets DMG-2.',
                '[キャラ1体]にDMG−2する.',
            ],
            'multiple subjects in subsentence for stat changes' => [
                'このキャラを除く all your characters get AP+1, DP+1, DMG+1.',
                'このキャラを除く味方キャラ全てにＡＰ＋１・ＤＰ＋１・ＤＭＧ＋１する。',
            ],
            'multiline subject' => [
                "カード名に\"孫\"を含む<呉>キャラ\n This character gets AP+1, DP+1, DMG+1. This character gains [Bonus:[Your opponent discards 1 card from the top of their deck.]]. \nこのキャラが場を離れたとき, ゴミ箱のこのアイテムを味方キャラ1体に無償で装備する.",
                "カード名に「孫」を含む＜呉＞キャラ\nこのキャラにＡＰ＋１・ＤＰ＋１・ＤＭＧ＋１する。このキャラは[ボーナス:[相手のデッキを１枚破棄する。]]を得る。\nこのキャラが場を離れたとき、ゴミ箱のこのアイテムを味方キャラ１体に無償で装備する。",
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
            'When this character supports another character (with ga)' => [
                'When this character supports another character.',
                'このキャラがサポートをしたとき. ',
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
                '{1 ally untapped AF character} gets DMG-1. This character gets DMG+1.',
                '[未行動の味方ＡＦキャラ１体]にＤＭＧ－１する。このキャラにＤＭＧ＋１する。',
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
                '[キャラ]を[味方フィールド]に移動する。',
            ],
            'resources getting provided' => [
                'Use during your opponent\'s turn. Discard 2 cards from the top of your deck. When destroyed, you get [moon][moon].',
                '相手ターン中に使用する。自分のデッキを２枚破棄する。破棄したとき、[月月]を発生する。',
            ],
            'when your opponent activates an ability' => [
                'Use when your opponent activates an ability.',
                '相手の能力の宣言に対応して使用する。',
            ],
            'equipping' => [
                'Use during your turn. Do not use during battle. Equip {1 item in your Discard Pile} to {1 character}. This effect can be used only once while this card is on the field.',
                '自ターン中に使用する。バトル中に使用できない。[自分のゴミ箱のアイテム１枚]を[キャラ１体]に装備する。この能力は失われる。',
            ],
            'once per turn cards of the same name' => [
                'This effect can only be used once per turn by cards of the same number',
                '同番号の能力は1ターンに1回まで処理可能',
            ],
            'quoted' => [
                'At the start of the turn, you can move 1 ally <Antsio> character to an ally field.',
                'ターン開始時、味方＜アンツィオ＞キャラ１体を味方フィールドに移動できる。',
            ],
            'opponent discarding from hand' => [
                'Your opponent discards 2 cards from their hand',
                '相手は相手の手札を2枚破棄する',
            ],
            'recover cards' => [
                'Recover 2 cards to your deck',
                '自分のデッキを2枚回復する',
            ],
            'do not untap' => [
                "The opponent's character does not get untapped at their next wake-up",
                '対戦キャラは次の相手のウェイクアップで未行動に戻らない',
            ],
            'stop the battle' => [
                "Stop the battle",
                'バトルを中断する',
            ],
            'until the end of the turn cannot be destroyed by battle' => [
                "This character cannot be destroyed by battle until the end of the turn",
                'このキャラはターン終了時までダウンしない',
            ],
            'until the end of the turn cannot be destroyed by battle 2' => [
                "Until the end of the turn {1 ally character} cannot be destroyed by battle",
                'ターン終了時まで[味方キャラ１体]はダウンしない',
            ],
            'Put 1 card from your hand on the top of your deck' => [
                'Put 1 card from your hand on the top of your deck',
                '自分の手札を1枚デッキの上に置く',
            ],
            'Can defend despite tapped' => [
                'All your [flower] characters can defend even while tapped.',
                '味方[花]キャラ全ては、行動済みでも防御キャラに指定できる。',
            ],
            'This character can defend despite tapped' => [
                'This character can defend even while tapped.',
                'このキャラは行動済みでも防御キャラに指定できる.',
            ],
            'does not wake up your next wakeup' => [
                'Do not untap this character in your next wake-up phase',
                'このキャラは次の自分のウェイクアップで未行動に戻らない',
            ],
            ' you can discard from your hand' => [
                'You can discard 1 card from your hand',
                '自分の手札を1枚破棄できる',
            ],
            ' can only support xy characters' => [
                'This card can only support a "xy".',
                'このキャラのサポートは「xy」のみを対象に指定できる。',
            ],
            'summon for free' => [
                'Summon {1 character} without paying its cost.',
                '[キャラ1体]を無償で登場する。',
            ],
            'once per turn or battle' => [
                'This effect can only be used 1 time during the game.',
                'ゲーム中１回まで使用可能。',
            ],
        ];
    }
}
