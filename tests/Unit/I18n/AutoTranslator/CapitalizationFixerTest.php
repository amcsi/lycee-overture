<?php
declare(strict_types=1);

namespace Tests\Unit\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\CapitalizationFixer;
use PHPUnit\Framework\TestCase;

class CapitalizationFixerTest extends TestCase
{
    /**
     * @dataProvider provideFixCapitalization
     */
    public function testFixCapitalization(string $expected, string $input)
    {
        self::assertSame($expected, CapitalizationFixer::fixCapitalization($input));
    }

    public function provideFixCapitalization()
    {
        return [
            [
                'As',
                'as',
            ],
            [
                'As. As.',
                'as. as.',
            ],
            'newlines before' => [
                "Something\nSomething",
                "something\nsomething",
            ],
            'newlines with spaces' => [
                "Something\n Something",
                "something\n something",
            ],
            'costs' => [
                "[Penalty:[Draw 1 card.]]",
                "[Penalty:[draw 1 card.]]",
            ],
            'Bullet points' => [
                "Something\n• Something",
                "something\n• something",
            ],
        ];
    }
}
