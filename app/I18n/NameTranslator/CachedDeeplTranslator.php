<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\NameTranslator;

use amcsi\LyceeOverture\I18n\JapaneseCharacterCounter;
use amcsi\LyceeOverture\I18n\TranslationUsedTracker;
use amcsi\LyceeOverture\I18n\TranslatorInterface;
use DeepL\Translator;

readonly class CachedDeeplTranslator implements TranslatorInterface
{
    public function __construct(
        private Translator $deeplTranslator,
        private DeeplCacheStore $cacheStore,
        private TranslationUsedTracker $translationUsedTracker
    ) {
    }

    public function translate(string $text, $dryRun = false): string
    {
        if (!$text) {
            return $text;
        }

        $characterCounter = $this->translationUsedTracker->getCharacterCounter();
        $characterCount = mb_strlen($text);

        $characterCounter->addCharactersAttempted($characterCount);

        if (! JapaneseCharacterCounter::countJapaneseCharacters($text)) {
            return $text;
        }

        $characterCounter->addCharactersPassed($characterCount);

        $this->translationUsedTracker->add($text);

        return $this->cacheStore->rememberForever(
            $text,
            function () use ($text, $dryRun, $characterCounter, $characterCount) {
                $characterCounter->addCharactersSent($characterCount);

                return $dryRun ? $text : $this->deeplTranslator->translateText($text, null, 'en-US')->text;
            }
        );
    }
}
