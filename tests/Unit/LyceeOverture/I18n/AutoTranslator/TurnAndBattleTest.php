<?php
declare(strict_types=1);

namespace Tests\Unit\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\TurnAndBattle;
use PHPUnit\Framework\TestCase;

class TurnAndBattleTest extends TestCase
{
    /**
     * @dataProvider provideAutoTranslate
     */
    public function testAutoTranslate(string $expected, string $input)
    {
        self::assertSame($expected, TurnAndBattle::autoTranslate($input));
    }

    public function provideAutoTranslate()
    {
        return [
            [
                'use during your turn',
                '自ターン中に使用する',
            ],
            [
                'use during your opponent\'s turn',
                '相手ターン中に使用する',
            ],
            [
                'during this turn',
                'このターン中',
            ],
            [
                'at the start of your opponent\'s turn',
                '相手ターン開始時',
            ],
            [
                'at the start of your turn',
                '自ターン開始時',
            ],
            [
                'at the start of your next turn',
                '次の自ターン開始時',
            ],
            [
                'until the end of your turn',
                '自ターン終了時まで',
            ],
            [
                'during your turn',
                '自ターン中',
            ],
            [
                'until the end of the turn',
                'ターン終了時まで',
            ],
            'imaginary case with do not use' => [
                'do not use during your turn',
                '自ターン中に使用できない',
            ],
        ];
    }
}
