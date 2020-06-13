<?php
declare(strict_types=1);

namespace Tests\Unit\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\StatChanges;
use PHPUnit\Framework\TestCase;

class StatChangesTest extends TestCase
{
    /**
     * @dataProvider provideAutoTranslate
     */
    public function testAutoTranslate(string $expected, string $input): void
    {
        self::assertSame($expected, StatChanges::autoTranslate($input));
    }

    public function provideAutoTranslate()
    {
        return [
            [
                '..とき opposing character gets AP+2, DP+2.',
                '..とき対戦キャラにAP+2, DP+2する.',
            ],
            [
                '..とき opposing character gets AP+2 or DP+2.',
                '..とき対戦キャラにAP+2またはDP+2する.',
            ],
            [
                "  {Opponent character's} AP and DP become 0.",
                '{Opponent character}のAPとDPを0にする.',
            ],
            'optional making 0' => [
                "  {Opponent character's} AP and DP can become 0.",
                '{Opponent character}のAPとDPを0にできる.',
            ],
            [
                "の a character's DMG becomes 4.",
                'のキャラのDMGを4にする.',
            ],
            'Target (that) character' => [
                " that character gets AP+1.",
                '対象のキャラにAP+1する.',
            ],
            'optional effect' => [
                " that character can get AP+1.",
                '対象のキャラにAP+1できる.',
            ],
            'Until the end of battle' => [
                " that character gets AP+1 until the end of battle.",
                '対象のキャラにバトル終了時までAP+1する.',
            ],
            'plus that characters SP' => [
                " this character gets AP+[this character's SP].",
                'このキャラにAP+[このキャラのSP]する.',
            ],
            'stats become this characters stats' => [
                "  {Opponent character's} AP and DP become [this character's SP].",
                '{Opponent character}のAPとDPを[このキャラのSP]にする.',
            ],
        ];
    }
}
