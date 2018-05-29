<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Import;

use amcsi\LyceeOverture\Card\Type;
use PHPUnit\Framework\TestCase;

class CsvValueInterpreterTest extends TestCase
{
    /**
     * @dataProvider provideGetType
     */
    public function testGetType($expected, $inputCell): void
    {
        self::assertSame($expected, CsvValueInterpreter::getType([CsvColumns::TYPE => $inputCell]));
    }

    public function provideGetType(): array
    {
        return [
            [Type::CHARACTER, 'キャラクター'],
            [Type::ITEM, 'アイテム'],
            [Type::EVENT, 'イベント'],
        ];
    }
}
