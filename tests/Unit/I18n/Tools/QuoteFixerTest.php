<?php
declare(strict_types=1);

namespace Tests\Unit\I18n\Tools;

use amcsi\LyceeOverture\I18n\Tools\QuoteFixer;
use PHPUnit\Framework\TestCase;

class QuoteFixerTest extends TestCase
{
    public function testFixQuotes()
    {
        self::assertSame('aaa “bbb” ccc “ddd” eee', QuoteFixer::fixQuotes('aaa "bbb" ccc "ddd" eee'));
    }
}
