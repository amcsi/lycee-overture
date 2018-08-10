<?php
declare(strict_types=1);

namespace Tests\Unit\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\WhenSupporting;
use PHPUnit\Framework\TestCase;

class WhenSupportingTest extends TestCase
{
    /**
     * @dataProvider provideAutoTranslate
     */
    public function testAutoTranslate($expected, $input)
    {
        self::assertSame($expected, WhenSupporting::autoTranslate($input));
    }

    public function provideAutoTranslate()
    {
        return [
            'getting supported by certain character' => [
                'when this character gets supported by a character',
                'キャラでこのキャラにサポートをしたとき',
            ],
        ];
    }
}
