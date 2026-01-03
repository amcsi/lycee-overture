<?php
declare(strict_types=1);

namespace Tests\Unit\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\OneOffs;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class OneOffsTest extends TestCase
{
    #[DataProvider('provideAutoTranslate')]
    public function testAutoTranslate($expected, $input)
    {
        self::assertSame($expected, OneOffs::autoTranslate($input));
    }

    public static function provideAutoTranslate()
    {
        return [
            'untap and move' => [
                'untap this character and move it to {unoccupied ally field}',
                'このキャラを未行動にし, {空き味方フィールド}に移動する',
            ],
        ];
    }
}
