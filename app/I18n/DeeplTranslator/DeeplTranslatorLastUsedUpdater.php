<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\DeeplTranslator;

use amcsi\LyceeOverture\DeeplTranslation;
use amcsi\LyceeOverture\I18n\TranslationUsedTracker;

class DeeplTranslatorLastUsedUpdater
{
    public function updateLastUsed()
    {
        $now = \DB::raw('CURRENT_TIMESTAMP(6)');
        $translationsUsedTracker = app(TranslationUsedTracker::class);
        foreach (array_chunk($translationsUsedTracker->get(), 1000) as $translationChunk) {
            DeeplTranslation::whereIn('source', $translationChunk)->update(['last_used_at' => $now]);
        }
        $translationsUsedTracker->flush();
    }
}
