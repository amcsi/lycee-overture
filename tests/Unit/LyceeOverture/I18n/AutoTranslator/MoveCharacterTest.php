<?php
declare(strict_types=1);

namespace Tests\Unit\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\MoveCharacter;
use PHPUnit\Framework\TestCase;

class MoveCharacterTest extends TestCase
{
    /**
     * @dataProvider provideAutoTranslate
     */
    public function testAutoTranslate(string $expected, string $input): void
    {
        self::assertSame($expected, MoveCharacter::autoTranslate($input));
    }

    public function provideAutoTranslate()
    {
        return [
            [
                ' move this character to {ally field}.',
                'このキャラを{ally field}に移動する.',
            ],
            [
                ' move {character} to {ally field}.',
                '{character}を{ally field}に移動する.',
            ],
            'optional' => [
                ' you can move {character} to {ally field}.',
                '{character}を{ally field}に移動できる.',
            ],
            'change places' => [
                ' this character and {1 ally character} change places',
                'このキャラと{1 ally character}を入れ替える',
            ],
        ];
    }
}
