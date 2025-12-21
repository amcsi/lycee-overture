<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\DeeplTranslator;

use amcsi\LyceeOverture\Models\DeeplTranslation;
use amcsi\LyceeOverture\I18n\TranslationUsedTracker;

class DeeplTranslatorLastUsedUpdater
{
    public function updateLastUsed()
    {
        $now = \DB::raw('CURRENT_TIMESTAMP(6)');
        $translationsUsedTracker = app(TranslationUsedTracker::class);
        $translationsUsed = array_values($translationsUsedTracker->get());
        $chunkSize = 1000;
        for ($i = 0, $iMax = count($translationsUsed); $i < $iMax; $i += $chunkSize) {
            $translationChunk = array_slice($translationsUsed, $i, $chunkSize);
            DeeplTranslation::whereIn('source', $translationChunk)->update(['last_used_at' => $now]);
        }
        $translationsUsedTracker->flush();
    }
}
