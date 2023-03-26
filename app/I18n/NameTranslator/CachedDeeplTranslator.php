<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\NameTranslator;

use amcsi\LyceeOverture\I18n\DeeplTranslator\DeeplMarkupTool;
use amcsi\LyceeOverture\I18n\JapaneseCharacterCounter;
use amcsi\LyceeOverture\I18n\Tools\JapaneseSentenceSplitter;
use amcsi\LyceeOverture\I18n\TranslationUsedTracker;
use amcsi\LyceeOverture\I18n\TranslatorInterface;
use amcsi\LyceeOverture\Import\CsvValueInterpreter\MarkupConverter;
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

        $self = $this;
        return JapaneseSentenceSplitter::replaceCallback(
            $text,
            (static fn(array $match) => $self->translateSentence($match[0], $dryRun))
        );
    }

    public function translateSentence(string $originalSentenceText, bool $dryRun): string
    {
        if (!$originalSentenceText) {
            return $originalSentenceText;
        }

        // We convert some Lycee markup into XML markup so DeepL would avoid translating that part.
        // https://www.deepl.com/docs-api/general/working-with-placeholder-tags/
        $deeplMarkupWrapped = DeeplMarkupTool::splitToMarkup($originalSentenceText);
        $text = $deeplMarkupWrapped->text;
        $translatedParts = collect($deeplMarkupWrapped->parts)->map(fn ($part) => MarkupConverter::convert($part))->toArray();

        $characterCounter = $this->translationUsedTracker->getCharacterCounter();
        $characterCount = mb_strlen($text);
        $characterCounter->addCharactersAttempted($characterCount);

        if (! JapaneseCharacterCounter::countJapaneseCharacters($text)) {
            return $text;
        }

        $characterCounter->addCharactersPassed($characterCount);

        $this->translationUsedTracker->add($text);

        $translated = $this->cacheStore->rememberForever(
            $text,
            function () use ($text, $dryRun, $characterCounter, $characterCount, $originalSentenceText) {
                $characterCounter->addCharactersSent($characterCount);

                if ($dryRun) {
                    echo "$originalSentenceText\n";
                    echo "$text\n";
                    echo "\n";
                }

                return $dryRun ? null : $this->deeplTranslator->translateText($text, 'ja', 'en-US')->text;
            }
        );

        return DeeplMarkupTool::reassembleString($translated, $translatedParts);
    }
}
