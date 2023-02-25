<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\NameTranslator;

use amcsi\LyceeOverture\DeeplTranslation;

class DeeplCacheStore
{
    public function get(string $text): ?string
    {
        return DeeplTranslation::whereSource($text)->value('translation');
    }

    public function rememberForever($text, callable $callback): string
    {
        if (($translation = $this->get($text)) === null) {
            $translation = $callback();
            $deeplTranslation = new DeeplTranslation();
            $deeplTranslation->source = $text;
            $deeplTranslation->translation = $translation;
            $deeplTranslation->save();
        }

        return $translation;
    }
}
