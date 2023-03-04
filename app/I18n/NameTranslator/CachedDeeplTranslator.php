<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\NameTranslator;

use amcsi\LyceeOverture\I18n\TranslatorInterface;
use DeepL\Translator;

class CachedDeeplTranslator implements TranslatorInterface
{
    public function __construct(
        private readonly Translator $deeplTranslator,
        private readonly DeeplCacheStore $cacheStore
    ) {
    }

    public function translate(string $text): string
    {
        if (!$text) {
            return $text;
        }

        return $this->cacheStore->rememberForever(
            $text,
            fn() => $this->deeplTranslator->translateText($text, null, 'en-US')->text
        );
    }
}
