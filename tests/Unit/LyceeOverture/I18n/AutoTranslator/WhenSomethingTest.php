<?php
declare(strict_types=1);

namespace Tests\Unit\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\WhenSomething;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class WhenSomethingTest extends TestCase
{
    #[DataProvider('provideAutoTranslate')]
    public function testAutoTranslate(string $expected, string $input)
    {
        self::assertSame($expected, WhenSomething::autoTranslate($input));
    }

    public static function provideAutoTranslate()
    {
        return [
            [
                'if an allied character has been Engage Summoned',
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
                'when discarded',
                '破棄したとき',
            ],
            [
                'when removed from play',
                '除外したとき',
            ],
            [
                'when an ally character gets engaged',
                '味方キャラがエンゲージ登場している場合',
            ],
            'when discarded (generic over subjects)' => [
                'when an ally character gets discarded',
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
            'when an ally character is equipped with an item' => [
                'when an ally character is equipped with an item',
                '味方キャラがアイテムを装備している場合',
            ],
            'when you discard from your deck' => [
                'when you discard from your deck',
                '自分のデッキを破棄したとき',
            ],
            'when this character inflicts damage' => [
                "when this character inflicts damage to your opponent's deck",
                'このキャラが相手のデッキにダメージを与えたとき',
            ],
            'while you have fewer characters on the field than your opponent' => [
                'while you have fewer characters on the field than your opponent',
                '相手キャラの数より味方キャラの数が少ない場合',
            ],
            'when this character is summoned except by Engage summon' => [
                'when this character is summoned except by Engage summon',
                'このキャラがエンゲージ登場以外で登場したとき',
            ],
            'when opponent defeated in battle' => [
                'when opposing character is defeated in battle',
                '対戦キャラがダウンしていた場合',
            ],
            'when there are n excluded characters' => [
                'when there are 7 or more of your characters removed from play',
                '除外された自分のキャラが7体以上の場合',
            ],
            'when equipped' => [
                'when a character is equipped with an item',
                'キャラがアイテムを装備したとき',
            ],
            'while equipped' => [
                'while a character is equipped with an item',
                'キャラがアイテムを装備している場合',
            ],
            'when there are 4 or more subjects' => [
                'when there are 4 or more opponent characters on the field',
                '相手キャラが4体以上の場合',
            ],
            'by xy effect' => [
                'when an ally character gets discarded by your effect',
                '自分の効果で味方キャラを破棄したとき',
            ],
        ];
    }
}
