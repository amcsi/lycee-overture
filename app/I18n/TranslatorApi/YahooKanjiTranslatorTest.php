<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\TranslatorApi;

use Illuminate\Cache\ArrayStore;
use Illuminate\Cache\Repository;
use PHPUnit\Framework\TestCase;

class YahooKanjiTranslatorTest extends TestCase
{
    /**
     * @dataProvider provideTranslate
     */
    public function testTranslate(string $expected, string $cacheResult)
    {
        $input = 'kanji input'; // Doesn't matter for this test.
        $arrayStore = new ArrayStore();
        $arrayRepository = new Repository($arrayStore);
        $arrayStore->forever($input, $cacheResult);
        $instance = new YahooKanjiTranslator(\Mockery::mock(YahooRawKanjiTranslator::class), $arrayRepository);
        self::assertSame($expected, $instance->translate($input));
    }

    public function provideTranslate(): array
    {
        return [
            'two word translation gets translated' => [
                'Two Words',
                'two words',
            ],
            'more than two words do not' => [
                'kanji input',
                'three three words',
            ],
            'one word does not' => [
                'kanji input',
                'one',
            ],
            'empty' => [
                'kanji input',
                '',
            ],
            'just space' => [
                'kanji input',
                ' ',
            ],
        ];
    }
}
