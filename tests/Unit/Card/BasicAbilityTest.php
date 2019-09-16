<?php
declare(strict_types=1);

namespace Tests\Unit\Card;

use amcsi\LyceeOverture\Card\BasicAbility;
use PHPUnit\Framework\TestCase;

class BasicAbilityTest extends TestCase
{
    public function testGetJapaneseToMarkup()
    {
        $result = BasicAbility::getJapaneseToMarkup();
        self::assertSame('Order Step', $result['オーダーステップ']);
    }
}
