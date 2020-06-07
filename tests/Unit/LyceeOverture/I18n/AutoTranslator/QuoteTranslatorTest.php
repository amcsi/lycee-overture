<?php
declare(strict_types=1);

namespace Tests\Unit\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\QuoteTranslator;
use amcsi\LyceeOverture\I18n\JpnForPhp\TransliteratorFactory;
use amcsi\LyceeOverture\I18n\NameTranslator\KanaTranslator;
use amcsi\LyceeOverture\I18n\NameTranslator\ManualNameTranslator;
use amcsi\LyceeOverture\I18n\NameTranslator\NameTranslator;
use PHPUnit\Framework\TestCase;
use Tests\Tools\TestUtils;

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
            (new QuoteTranslator(
                new NameTranslator(
                    new ManualNameTranslator($translations),
                    new KanaTranslator(TransliteratorFactory::getInstance()),
                    TestUtils::createKanjiTranslator()
                )
            ))->autoTranslate('asdf ＜魔剣＞ asdf 「lol」 hey 「lol2」')
        );
    }
}
