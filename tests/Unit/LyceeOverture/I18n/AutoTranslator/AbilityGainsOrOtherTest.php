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
                ' opposing character gets destroyed.',
                '対戦キャラを破棄する.',
            ],
            [
                ' this character gets destroyed.',
                'このキャラを破棄する.',
            ],
            [
                ' 1 enemy character gets destroyed.',
                '相手キャラ1体を破棄する.',
            ],
            [
                ' 1 ally character gets destroyed.',
                '味方キャラ1体を破棄する.',
            ],
            [
                ' this character gets untapped.',
                'このキャラを未行動にする.',
            ],
            [
                ' this character gets tapped.',
                'このキャラを行動済みにする.',
            ],
            'optional' => [
                ' this character can be tapped.',
                'このキャラを行動済みにできる.',
            ],
            [
                ' this character gets returned to hand.',
                'このキャラを手札に入れる.',
            ],
            'summoned' => [
                ' this character gets summoned.',
                'このキャラを登場する.',
            ],
            'sent to bottom of deck' => [
                ' this character gets sent to the bottom of the deck.',
                'このキャラをデッキの下に置く.',
            ],
            'sent to top of deck' => [
                ' this character gets sent to the top of the deck.',
                'このキャラをデッキの上に置く.',
            ],
            'removed from play' => [
                ' this character gets removed from play.',
                'このキャラを除外する.',
            ],
        ];
    }
}
