<?php
declare(strict_types=1);

namespace Tests\Unit\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\CannotBeDestroyed;
use PHPUnit\Framework\TestCase;

class CannotBeDestroyedTest extends TestCase
{
    /**
     * @dataProvider provideAutoTranslate
     */
    public function testAutoTranslate(string $expected, string $input)
    {
        self::assertSame($expected, CannotBeDestroyed::autoTranslate($input));
    }

    public function provideAutoTranslate(): array
    {
        return [
            ' that character cannot be destroyed by battle until the end of the turn' => [
                ' that character cannot be downed by battle until the end of the turn',
                'そのキャラはターン終了時までダウンしない',
            ],
        ];
    }
}
