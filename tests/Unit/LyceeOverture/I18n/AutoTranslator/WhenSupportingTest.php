<?php
declare(strict_types=1);

namespace Tests\Unit\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\WhenSupporting;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class WhenSupportingTest extends TestCase
{
    #[DataProvider('provideAutoTranslate')]
    public function testAutoTranslate($expected, $input)
    {
        self::assertSame($expected, WhenSupporting::autoTranslate($input));
    }

    public static function provideAutoTranslate()
    {
        return [
            'getting supported by certain character' => [
                'when this character gets supported by a character',
                'キャラでこのキャラにサポートをしたとき',
            ],
            'getting certain character supported by certain character' => [
                'when an 「Ankou Team」 character gets supported by this character',
                'このキャラで「Ankou Team」キャラにサポートをしたとき',
            ],
        ];
    }
}
