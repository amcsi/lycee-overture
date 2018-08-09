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
                            'names' =>
                                [
                                    'lol' => 'translation',
                                ],
                            'ability_names' =>
                                [
                                    'lol2' => 'translation2',
                                ],
                        ],
                ],
        ];

        self::assertSame(
            'asdf ＜Magic Sword＞ asdf 「translation」 hey 「translation2」',
            (new QuoteTranslator($translations))->autoTranslate('asdf ＜魔剣＞ asdf 「lol」 hey 「lol2」')
        );
    }
}
