<?php
declare(strict_types=1);

namespace Tests\Unit\I18n\NameTranslator;

use amcsi\LyceeOverture\I18n\NameTranslator\KanjiTranslator;
use amcsi\LyceeOverture\I18n\TranslatorApi\YahooKanjiTranslator;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class KanjiTranslatorTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @dataProvider provideTranslate
     * @param string $expected The expected (mocked) result.
     * @param string $input The name to translate.
     * @param array $translated An array of strings the mocked Yahoo service should return in order.
     */
    public function testTranslate(string $expected, string $input, array $translated = []): void
    {
        $yahooTranslator = \Mockery::mock(YahooKanjiTranslator::class);
        foreach ($translated as $mockTranslated) {
            $yahooTranslator->expects()->translate()->withAnyArgs()->andReturn($mockTranslated);
        }
        $instance = new KanjiTranslator($yahooTranslator);
        self::assertSame($expected, $instance->translate($input));
    }

    public function provideTranslate()
    {
        return [
            'English name' => [
                'english',
                'english',
            ],
            'Kanji name' => [
                'Isuzu Hana',
                '左衛門佐',
                ['Isuzu Hana'],
            ],
            'Kanji names separated by slashes' => [
                'Isuzu Hana／Isuzu Hana',
                '左衛門佐／左衛門佐',
                ['Isuzu Hana', 'Isuzu Hana'],
            ],
            'Contains katakana' => [
                '左衛門佐チ',
                '左衛門佐チ',
            ],
        ];
    }
}
