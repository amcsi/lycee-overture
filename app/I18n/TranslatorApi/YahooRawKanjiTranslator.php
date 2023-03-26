<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\TranslatorApi;

use amcsi\LyceeOverture\I18n\TranslatorInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Utils;
use Illuminate\Support\Str;

/**
 * Translates kanji (names) with Yahoo's API.
 */
class YahooRawKanjiTranslator implements TranslatorInterface
{
    public function __construct(private Client $client, private string $apiKey)
    {
    }

    public function translate(string $text, string $locale): string
    {
        $query = [
            'appid' => $this->apiKey,
            'sentence' => $text,
        ];
        $body = [
            'id' => Str::random(),
            'jsonrpc' => '2.0',
            'method' =>  'jlp.furiganaservice.furigana',
            'params' => [
                'q'=>  "大野 あや"
            ],
        ];
        $options = [RequestOptions::QUERY => $query, RequestOptions::JSON => $body];
        try {
            $responseBody = Utils::jsonDecode($this->client
                ->post('https://jlp.yahooapis.jp/FuriganaService/V2/furigana', $options)
                ->getBody()
                ->__toString(), true);
        } catch (ServerException $exception) {
            if (
                $exception->getCode() === 503 &&
                ($response = $exception->getResponse()) &&
                str_contains((string) $response->getBody(), 'invalid parameter: sentence')
            ) {
                // Bad kanji. Just return the input string.
                return $text;
            }
            throw $exception;
        }

        $translationResult = new TranslationResult($responseBody['result']['word']);

        return implode(
            ' ',
            $translationResult->getWords()
        );
    }
}
