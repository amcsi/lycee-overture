<?php
declare(strict_types=1);

namespace Tests\Unit\LyceeOverture\I18n\TranslatorApi;

use amcsi\LyceeOverture\I18n\TranslatorApi\TranslationResult;
use PHPUnit\Framework\TestCase;

class TranslationResultTest extends TestCase
{
    public static function testReadResult(): void
    {
        $result = new TranslationResult(file_get_contents(__DIR__ . '/result.xml'));

        self::assertSame(['isuzu', 'hana'], $result->getWords());
    }
}
