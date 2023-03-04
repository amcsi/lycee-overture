<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\NameTranslator;

use amcsi\LyceeOverture\DeeplTranslation;
use amcsi\LyceeOverture\I18n\TranslationUsedTracker;
use Illuminate\Database\Eloquent\Collection;

class DeeplCacheStore
{
    private $translationsBySource;

    public function get(string $text): ?string
    {
        return $this->getAllBySource()->get($text)?->translation;
    }

    public function rememberForever($text, callable $callback): string
    {
        $translationsBySource = $this->getAllBySource();
        if (($deeplTranslation = $translationsBySource->get($text)) === null) {
            $translation = $callback();
            $deeplTranslation = new DeeplTranslation();
            $deeplTranslation->source = $text;
            $deeplTranslation->translation = $translation;
            $translationsBySource[$text] = $deeplTranslation;
            $deeplTranslation->save();
        }

        app(TranslationUsedTracker::class)->add($text);

        return $deeplTranslation->translation;
    }

    /**
     * @return Collection|DeeplTranslation[]
     */
    private function getAllBySource(): Collection
    {
        return $this->translationsBySource ??= DeeplTranslation::all()->keyBy('source');
    }
}
