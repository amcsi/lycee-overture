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
                '..とき battling opponent\'s  character gets AP+2, DP+2.',
                '..とき対戦キャラにAP+2, DP+2する.',
            ],
        ];
    }
}
