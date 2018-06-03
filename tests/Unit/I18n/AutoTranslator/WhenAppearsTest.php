<?php
declare(strict_types=1);

namespace Tests\Unit\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\WhenAppears;
use PHPUnit\Framework\TestCase;

class WhenAppearsTest extends TestCase
{
    public function testAutoTranslate()
    {
        self::assertSame('when this character enters the field', WhenAppears::autoTranslate('このキャラが登場したとき'));
    }
}
