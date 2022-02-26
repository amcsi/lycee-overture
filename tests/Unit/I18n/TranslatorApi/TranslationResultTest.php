<?php
declare(strict_types=1);

namespace Tests\Unit\I18n\TranslatorApi;

use amcsi\LyceeOverture\I18n\TranslatorApi\TranslationResult;
use PHPUnit\Framework\TestCase;

class TranslationResultTest extends TestCase
{
    public static function testReadResult(): void
    {
        $result = new TranslationResult([
            [
                'furigana' => 'おおの',
                'roman' => 'oono',
                'surface' => '大野',
            ],
            [
                'surface' => ' ',
            ],
            [
                'furigana' => 'あや',
                'roman' => 'aya',
                'surface' => 'あや',
            ],
        ]);

        self::assertSame(['oono', 'aya'], $result->getWords());
    }
}
