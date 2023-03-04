<?php
declare(strict_types=1);

namespace Tests\Unit\LyceeOverture\I18n\NameTranslator;

use amcsi\LyceeOverture\I18n\JpnForPhp\TransliteratorFactory;
use amcsi\LyceeOverture\I18n\NameTranslator\KanaTranslator;
use PHPUnit\Framework\TestCase;

class KanaTranslatorTest extends TestCase
{
    /**
     * @dataProvider provideTranslate
     */
    public function testTranslate($expected, $input)
    {
        $instance = new KanaTranslator(TransliteratorFactory::getInstance());
        self::assertSame($expected, $instance->translate($input));
    }

    public static function provideTranslate()
    {
        return [
            [
                'Momogaa',
                'ももがー',
            ],
            [
                'Kuraara',
                'クラーラ',
            ],
            'hiragana' => [
                'Oryou',
                'おりょう',
            ],
            'do not translate mixtures' => [
                '天使の力',
                '天使の力',
            ],
            'foreign katakana' => [
                'Vareiriivuvetu',
                'ヴぁレェリィヴヴェトゥ',
            ],
            'separated by romaji' => [
                'Eremia VS Adeere',
                'エレミアVSアデーレ',
            ],
        ];
    }
}
