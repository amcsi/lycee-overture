<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\TranslatorApi;

use amcsi\LyceeOverture\I18n\TranslatorInterface;
use Illuminate\Contracts\Cache\Repository;

class YahooKanjiTranslator implements TranslatorInterface
{
    private $yahooRawKanjiTranslator;
    private $cache;

    public function __construct(YahooRawKanjiTranslator $yahooRawKanjiTranslator, Repository $cache)
    {
        $this->yahooRawKanjiTranslator = $yahooRawKanjiTranslator;
        $this->cache = $cache;
    }

    public function translate(string $text): string
    {
        // Try to get the cached translation, otherwise use the service to call the Yahoo API.
        $translated = $this->cache->get($text);
        if ($translated === null) {
            $translated = $this->yahooRawKanjiTranslator->translate($text);
            $expiry = 60 * 24 * 30 * 12; // 1 year.
            $this->cache->put($text, $translated, $expiry);
        }

        $words = explode(' ', trim($translated));
        if (\count($words) !== 2) {
            // Do not translate. The result is not two words so it can't be a typical Japanese name.
            return $text;
        }

        // Capitalize first letter of each word.
        $translated = implode(
            ' ',
            array_map(
                function (string $word) {
                    return ucfirst($word);
                },
                $words
            )
        );

        return $translated;
    }
}
