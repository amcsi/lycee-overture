<?php
declare(strict_types=1);

namespace Tests\Feature\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\SpaceAfterPeriodFixer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class SpaceAfterPeriodFixerTest extends TestCase
{

    #[DataProvider('provideFix')]
    public function testFix($expected, $input)
    {
        self::assertSame($expected, SpaceAfterPeriodFixer::fix($input));
    }

    public static function provideFix(): array
    {
        return [
            ['hey', 'hey'],
            ['hey. yo', 'hey.yo'],
            ["hey.\nyo", "hey.\nyo"],
            ["hey.", "hey."],
            ['hey. yo. bla', 'hey.yo.bla'],
            ['hey. yo. bla', 'hey. yo. bla'],
        ];
    }
}
