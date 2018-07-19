<?php
declare(strict_types=1);

namespace Tests\Unit\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\DrawCards;
use PHPUnit\Framework\TestCase;

class DrawCardsTest extends TestCase
{
    public function testAutoTranslate()
    {
        self::assertSame('draw 2 cards', DrawCards::autoTranslate('2枚ドローする'));
        self::assertSame('you can draw 2 cards', DrawCards::autoTranslate('2枚ドローできる'));
    }
}
