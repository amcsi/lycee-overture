<?php
declare(strict_types=1);

namespace Tests\Unit\I18n\TranslatorApi;

use amcsi\LyceeOverture\I18n\TranslatorApi\YahooRawKanjiTranslator;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class YahooRawKanjiTranslatorTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testTranslate(): void
    {
        $apiKey = 'whatever';
        $kanjiInput = '左衛門佐';
        $guzzleClient = \Mockery::mock(Client::class);
        $options = [
            'query' => [
                'appid' => $apiKey,
                'sentence' => $kanjiInput,
            ],
        ];
        $guzzleClient->expects()
            ->get('https://jlp.yahooapis.jp/FuriganaService/V1/furigana', $options)
            ->andReturn(
                new Response(200, [], file_get_contents(__DIR__ . '/result.xml'))
            );

        $instance = new YahooRawKanjiTranslator($guzzleClient, $apiKey);

        self::assertSame('isuzu hana', $instance->translate($kanjiInput));
    }

    public function testBadSentenceReturnsSameKanji(): void
    {
        $apiKey = 'whatever';
        $kanjiInput = 'bad_kanji';
        $guzzleClient = new Client(['handler' => HandlerStack::create(new MockHandler([
            function (Request $request) use ($apiKey, $kanjiInput) {
                self::assertSame(
                    "https://jlp.yahooapis.jp/FuriganaService/V1/furigana?appid=$apiKey&sentence=$kanjiInput",
                    (string) $request->getUri()
                );
                return new Response(503, [], 'bla bla bla invalid parameter: sentence bla');
            },
        ]))]);

        $instance = new YahooRawKanjiTranslator($guzzleClient, $apiKey);

        self::assertSame($kanjiInput, $instance->translate($kanjiInput));
    }
}
