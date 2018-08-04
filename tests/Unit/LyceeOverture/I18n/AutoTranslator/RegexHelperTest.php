<?php
declare(strict_types=1);

namespace Tests\Unit\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\RegexHelper;
use PHPUnit\Framework\TestCase;

class RegexHelperTest extends TestCase
{
    public function testUncapture()
    {
        self::assertSame('(?:[](?:(?:a(?=b))))', RegexHelper::uncapture('([]((?:a(?=b))))'));
        self::assertSame(
            '(?:z(?:[](?:(?:a(?=b)))))',
            RegexHelper::uncapture('z([]((?:a(?=b))))'),
            'should enclose with parentheses to be safe and not affect any regexes the result would get placed into.'
        );
    }
}
