<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\TranslatorApi;

use amcsi\LyceeOverture\I18n\TranslatorInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;

/**
 * Translates kanji (names) with Yahoo's API.
 */
class YahooRawKanjiTranslator implements TranslatorInterface
{
    public function __construct(private Client $client, private string $apiKey)
    {
    }

    public function translate(string $kanji): string
    {
        $query = [
            'appid' => $this->apiKey,
            'sentence' => $kanji,
        ];
        $options = ['query' => $query];
        try {
            $apiResponseBodyXml = $this->client
                ->get('https://jlp.yahooapis.jp/FuriganaService/V1/furigana', $options)
                ->getBody()
                ->__toString();
        } catch (ServerException $exception) {
            if (
                $exception->getCode() === 503 &&
                ($response = $exception->getResponse()) &&
                str_contains((string) $response->getBody(), 'invalid parameter: sentence')
            ) {
                // Bad kanji. Just return the input string.
                return $kanji;
            }
            throw $exception;
        }

        $translationResult = new TranslationResult($apiResponseBodyXml);

        return implode(
            ' ',
            $translationResult->getWords()
        );
    }
}
