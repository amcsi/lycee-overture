<?php
declare(strict_types=1);

namespace Tests\Unit\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\Equip;
use PHPUnit\Framework\TestCase;

class EquipTest extends TestCase
{
    /**
     * @dataProvider provideAutoTranslate
     */
    public function testAutoTranslate(string $expected, string $input)
    {
        self::assertSame($expected, Equip::autoTranslate($input));
    }

    public function provideAutoTranslate(): array
    {
        return [
            [
                'equip {1 item in your graveyard} to {1 character}',
                '{1 item in your graveyard}を{1 character}に装備する',
            ],
        ];
    }
}
