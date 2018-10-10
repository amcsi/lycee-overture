<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\TranslatorApi;

use amcsi\LyceeOverture\I18n\TranslatorInterface;
use GuzzleHttp\Client;

/**
 * Translates kanji (names) with Yahoo's API.
 */
class YahooRawKanjiTranslator implements TranslatorInterface
{
    private $client;
    private $apiKey;

    public function __construct(Client $client, string $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    public function translate(string $kanji): string
    {
        $query = [
            'appid' => $this->apiKey,
            'sentence' => $kanji,
        ];
        $options = ['query' => $query];
        $apiResponseBodyXml = $this->client
            ->get('https://jlp.yahooapis.jp/FuriganaService/V1/furigana', $options)
            ->getBody()
            ->__toString();

        $translationResult = new TranslationResult($apiResponseBodyXml);

        return implode(
            ' ',
            $translationResult->getWords()
        );
    }
}
