<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\NameTranslator;

use amcsi\LyceeOverture\Models\DeeplTranslation;
use Illuminate\Support\Collection;

class DeeplCacheStore
{
    private $translationsBySource = [];

    public function get(string $text, string $locale): ?string
    {
        return $this->getAllBySource($locale)->get($text);
    }

    public function rememberForever($text, string $locale, callable $callback): string
    {
        $translationsBySource = $this->getAllBySource($locale);
        $translation = $translationsBySource->get($text);
        if (! $translation) {
            /** @var string|null $translation */
            $translation = $callback();
            $deeplTranslation = new DeeplTranslation();
            $deeplTranslation->source = $text;
            $deeplTranslation->translation = $translation;
            $deeplTranslation->locale = $locale;
            if ($translation !== null) {
                $deeplTranslation->save();
                $translationsBySource->put($text, $translation);
            }
        }

        // Translation can only be NULL in a dry run.
        return $translation ?? $text;
    }

    public function flush()
    {
        $this->translationsBySource = [];
    }

    /**
     * @return Collection|string[]
     */
    private function getAllBySource(string $locale): Collection
    {
        return $this->translationsBySource[$locale] ??= DeeplTranslation::query()
            ->where('locale', $locale)
            ->lazy()
            ->mapWithKeys(function (DeeplTranslation $translation) {
                return [$translation->source => $translation->translation];
            })
            ->collect();
    }
}
