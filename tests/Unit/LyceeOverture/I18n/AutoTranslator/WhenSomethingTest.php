<?php
declare(strict_types=1);

namespace Tests\Unit\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\WhenSomething;
use PHPUnit\Framework\TestCase;

class WhenSomethingTest extends TestCase
{
    public function testAutoTranslate()
    {
        self::assertSame('when an ally character enters engagement', WhenSomething::autoTranslate('味方キャラがエンゲージ登場したとき'));
    }
}
