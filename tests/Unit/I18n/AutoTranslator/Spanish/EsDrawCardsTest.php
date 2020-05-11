<?php
declare(strict_types=1);

namespace Tests\Unit\I18n\AutoTranslator\Spanish;

use amcsi\LyceeOverture\I18n\AutoTranslator\DrawCards;
use amcsi\LyceeOverture\I18n\Locale;
use PHPUnit\Framework\TestCase;

class EsDrawCardsTest extends TestCase
{
    /**
     * @dataProvider provideAutoTranslate
     */
    public function testAutoTranslate(string $expected, string $input)
    {
        self::assertSame($expected, DrawCards::autoTranslate($input, Locale::SPANISH));
    }

    public function provideAutoTranslate()
    {
        return [
            [
                'roba 2 cartas',
                '2枚ドローする',
            ],
            [
                'puedes robar 2 cartas',
                '2枚ドローできる',
            ],
            [
                'tu adversario roba 2 cartas',
                '相手は2枚ドローする',
            ],
            [
                'tu adversario puede robar 2 cartas',
                '相手は2枚ドローできる',
            ],
        ];
    }
}
