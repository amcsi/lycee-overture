<?php
declare(strict_types=1);

namespace Tests\Unit\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\FullWidthCharacters;
use PHPUnit\Framework\TestCase;

class FullWidthCharactersTest extends TestCase
{
    public function testTranslateFullWidthCharacterNumbers()
    {
        self::assertSame('09', FullWidthCharacters::translateFullWidthCharacters('０９'));
    }

    public function testTranslateFullWidthCharacterLetters()
    {
        self::assertSame('AZ', FullWidthCharacters::translateFullWidthCharacters('ＡＺ'));
    }
}
