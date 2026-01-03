<?php
declare(strict_types=1);

namespace Tests\Unit\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\Equip;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class EquipTest extends TestCase
{
    #[DataProvider('provideAutoTranslate')]
    public function testAutoTranslate(string $expected, string $input)
    {
        self::assertSame($expected, Equip::autoTranslate($input));
    }

    public static function provideAutoTranslate(): array
    {
        return [
            [
                'equip {1 item in your Discard Pile} to {1 character}',
                '{1 item in your Discard Pile}を{1 character}に装備する',
            ],
        ];
    }
}
