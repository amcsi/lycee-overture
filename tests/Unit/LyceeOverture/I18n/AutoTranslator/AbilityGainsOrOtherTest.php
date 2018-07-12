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
                ' opponent\'s battling character gets discarded.',
                '対戦キャラを破棄する.',
            ],
            [
                ' this character gets discarded.',
                'このキャラを破棄する.',
            ],
            [
                ' this character gets untapped.',
                'このキャラを未行動にする.',
            ],
            [
                ' this character gets tapped.',
                'このキャラを行動済みにする.',
            ],
        ];
    }
}
