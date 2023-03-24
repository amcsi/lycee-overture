<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\NameTranslator;

use amcsi\LyceeOverture\DeeplTranslation;
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
        if (! $translationsBySource->has($text)) {
            $translation = $callback();
            $deeplTranslation = new DeeplTranslation();
            $deeplTranslation->source = $text;
            $deeplTranslation->translation = $translation;
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
    private function getAllBySource(): Collection
    {
        return $this->translationsBySource ??= DeeplTranslation::all()->keyBy('source');
    }
}
