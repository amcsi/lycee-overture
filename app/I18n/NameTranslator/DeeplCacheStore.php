<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\NameTranslator;

use amcsi\LyceeOverture\DeeplTranslation;
use Illuminate\Database\Eloquent\Collection;

class DeeplCacheStore
{
    private $translationsBySource;

    public function get(string $text, string $locale): ?string
    {
        return $this->getAllBySource($locale)->get($text)?->translation;
    }

    public function rememberForever($text, string $locale, callable $callback): string
    {
        $translationsBySource = $this->getAllBySource($locale);
        if (! $translationsBySource->has($text)) {
            $translation = $callback();
            $deeplTranslation = new DeeplTranslation();
            $deeplTranslation->source = $text;
            $deeplTranslation->translation = $translation;
            $deeplTranslation->locale = $locale;
            $translationsBySource[$text] = $deeplTranslation;
            if ($translation !== null) {
                $deeplTranslation->save();
            }
        } else {
            $deeplTranslation = $translationsBySource[$text];
        }

        // Translation can only be NULL in a dry run.
        return $deeplTranslation->translation ?? $text;
    }

    /**
     * @return Collection|DeeplTranslation[]
     */
    private function getAllBySource(string $locale): Collection
    {
        $this->translationsBySource ??= DeeplTranslation::all()
            ->groupBy('locale')
            ->map(fn(Collection $group) => $group->keyBy('source'));

        if (!$this->translationsBySource->get($locale)) {
            $this->translationsBySource[$locale] = new Collection();
        }

        return $this->translationsBySource[$locale];
    }
}
