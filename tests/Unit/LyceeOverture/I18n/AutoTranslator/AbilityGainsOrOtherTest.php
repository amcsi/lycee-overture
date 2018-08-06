<?php
declare(strict_types=1);

namespace Tests\Unit\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\AbilityGainsOrOther;
use PHPUnit\Framework\TestCase;

class AbilityGainsOrOtherTest extends TestCase
{
    /**
     * @dataProvider provideAutoTranslate
     * @param $expected
     * @param $input
     */
    public static function testAutoTranslate(string $expected, string $input): void
    {
        self::assertSame($expected, AbilityGainsOrOther::autoTranslate($input));
    }

    public function provideAutoTranslate(): array
    {
        return [
            [
                ' destroy opposing character.',
                '対戦キャラを破棄する.',
            ],
            [
                ' destroy this character.',
                'このキャラを破棄する.',
            ],
            [
                ' destroy 1 enemy character.',
                '相手キャラ1体を破棄する.',
            ],
            [
                ' destroy 1 ally character.',
                '味方キャラ1体を破棄する.',
            ],
            [
                ' untap this character.',
                'このキャラを未行動にする.',
            ],
            [
                ' tap this character.',
                'このキャラを行動済みにする.',
            ],
            'optional' => [
                ' you can tap this character.',
                'このキャラを行動済みにできる.',
            ],
            [
                " return this character to its owner's hand.",
                'このキャラを手札に入れる.',
            ],
            'summoned' => [
                ' summon this character.',
                'このキャラを登場する.',
            ],
            'sent to bottom of deck' => [
                ' send this character to the bottom of the deck.',
                'このキャラをデッキの下に置く.',
            ],
            'sent to top of deck' => [
                ' send this character to the top of the deck.',
                'このキャラをデッキの上に置く.',
            ],
            'removed from play' => [
                ' remove this character from play.',
                'このキャラを除外する.',
            ],
            ' your opponent destroys 1 of his characters' => [
                ' your opponent destroys 1 enemy character',
                '相手は相手キャラ1体を破棄する',
            ],
        ];
    }
}
