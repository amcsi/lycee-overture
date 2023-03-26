<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\TranslatorApi;

use amcsi\LyceeOverture\I18n\TranslatorInterface;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Cache\Repository;

class YahooKanjiTranslator implements TranslatorInterface
{
    public function __construct(private YahooRawKanjiTranslator $yahooRawKanjiTranslator, private Repository $cache)
    {
    }

    public function translate(string $text, string $locale): string
    {
        // Try to get the cached translation, otherwise use the service to call the Yahoo API.
        $translated = $this->cache->get($text);
        if ($translated === null) {
            $translated = $this->yahooRawKanjiTranslator->translate($text, $locale);
            if ($translated === $text) {
                // Couldn't be translated with the translation service.
                return $text;
            }
            $this->cache->put($text, $translated, CarbonImmutable::now()->addYear());
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
