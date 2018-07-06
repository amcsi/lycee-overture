<?php
declare(strict_types=1);

namespace Tests\Unit\LyceeOverture\I18n;

use amcsi\LyceeOverture\I18n\JapaneseCharacterCounter;
use PHPUnit\Framework\TestCase;

class JapaneseCharacterCounterTest extends TestCase
{
    public function testCountJapaneseCharacters()
    {
        // Only Hiragana, Katakana, and Kanji should count.
        self::assertSame(3, JapaneseCharacterCounter::countJapaneseCharacters('こン中。.ké'));
    }
}
