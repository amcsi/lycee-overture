<?php
declare(strict_types=1);

namespace Tests\Tools;

use amcsi\LyceeOverture\I18n\AutoTranslator;
use amcsi\LyceeOverture\I18n\AutoTranslator\QuoteTranslator;
use amcsi\LyceeOverture\I18n\JpnForPhp\TransliteratorFactory;
use amcsi\LyceeOverture\I18n\NameTranslator\KanaTranslator;
use amcsi\LyceeOverture\I18n\NameTranslator\ManualNameTranslator;
use amcsi\LyceeOverture\I18n\NameTranslator\NameTranslator;

class TestUtils
{
    public static function createAutoTranslator(): AutoTranslator
    {
        $quoteTranslator = new QuoteTranslator(
            new NameTranslator(new ManualNameTranslator([]), new KanaTranslator(TransliteratorFactory::getInstance()))
        );
        return new AutoTranslator($quoteTranslator);
    }
}
