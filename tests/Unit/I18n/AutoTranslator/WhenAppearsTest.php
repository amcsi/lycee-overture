<?php
declare(strict_types=1);

namespace Tests\Unit\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\WhenAppears;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class WhenAppearsTest extends TestCase
{
    #[DataProvider('provideAutoTranslate')]
    public function testAutoTranslate(string $expected, string $input)
    {
        self::assertSame($expected, WhenAppears::autoTranslate($input));
    }

    public static function provideAutoTranslate(): array
    {
        return [
            [
                'when this character is summoned',
                'このキャラが登場したとき',
            ],
            'other subject' => [
                'when an ally character is summoned',
                '味方キャラが登場したとき',
            ],
            'while summoning' => [
                'if an ally character is on the field',
                '味方キャラが登場している場合',
            ],
        ];
    }
}
