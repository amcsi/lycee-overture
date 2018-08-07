<?php
declare(strict_types=1);

namespace Tests\Unit\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\QuoteTranslator;
use PHPUnit\Framework\TestCase;

class QuoteTranslatorTest extends TestCase
{
    public function testAutoTranslate()
    {
        $translations = [
            'en' =>
                [
                    'translation' =>
                        [
                            'character_types' =>
                                [
                                    '魔剣' => 'Magic Sword',
                                ],
                        ],
                ],
        ];

        self::assertSame(
            'asdf <Magic Sword> asdf',
            (new QuoteTranslator($translations))->autoTranslate('asdf <魔剣> asdf')
        );
    }
}
