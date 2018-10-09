<?php
declare(strict_types=1);

namespace Tests\Unit\I18n\TranslatorApi;

use amcsi\LyceeOverture\I18n\TranslatorApi\KanjiTranslator;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class KanjiTranslatorTest extends TestCase
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

        $instance = new KanjiTranslator($guzzleClient, $apiKey);

        self::assertSame('Isuzu Hana', $instance->translate($kanjiInput));
    }
}
