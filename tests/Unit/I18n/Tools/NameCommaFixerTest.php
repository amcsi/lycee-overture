<?php
declare(strict_types=1);

namespace Tests\Unit\I18n\Tools;

use amcsi\LyceeOverture\I18n\Tools\NameCommaFixer;
use PHPUnit\Framework\TestCase;

class NameCommaFixerTest extends TestCase
{

    public function testFix()
    {
        self::assertSame(
            '“something something” “something something something”',
            NameCommaFixer::fix('“something, something” “something, something, something”')
        );
    }
}
