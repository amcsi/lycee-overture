<?php
declare(strict_types=1);

namespace Tests\Tools;

use amcsi\LyceeOverture\I18n\AutoTranslator;
use amcsi\LyceeOverture\I18n\AutoTranslator\QuoteTranslator;
use amcsi\LyceeOverture\I18n\JpnForPhp\TransliteratorFactory;
use amcsi\LyceeOverture\I18n\NameTranslator\KanaTranslator;
use amcsi\LyceeOverture\I18n\NameTranslator\KanjiTranslator;
use amcsi\LyceeOverture\I18n\NameTranslator\ManualNameTranslator;
use amcsi\LyceeOverture\I18n\NameTranslator\NameTranslator;
use amcsi\LyceeOverture\I18n\TranslatorInterface;

class TestUtils
{
    public static function createAutoTranslator(): AutoTranslator
    {
        $quoteTranslator = new QuoteTranslator(
            new NameTranslator(
                new ManualNameTranslator([]),
                new KanaTranslator(TransliteratorFactory::getInstance()),
                self::createKanjiTranslator()
            )
        );
        return new AutoTranslator($quoteTranslator);
    }

    public static function createKanjiTranslator(): KanjiTranslator
    {
        return new KanjiTranslator(new class implements TranslatorInterface {
            public function translate(string $text): string
            {
                // Return the text as-is.
                return $text;
            }
        });
    }
}
