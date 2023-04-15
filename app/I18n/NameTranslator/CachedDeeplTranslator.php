<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\NameTranslator;

use amcsi\LyceeOverture\I18n\DeeplTranslator\DeeplMarkupTool;
use amcsi\LyceeOverture\I18n\JapaneseCharacterCounter;
use amcsi\LyceeOverture\I18n\Locale;
use amcsi\LyceeOverture\I18n\Tools\JapaneseSentenceSplitter;
use amcsi\LyceeOverture\I18n\TranslationUsedTracker;
use amcsi\LyceeOverture\I18n\TranslatorInterface;
use amcsi\LyceeOverture\Import\CsvValueInterpreter\MarkupConverter;
use DeepL\TranslateTextOptions;
use DeepL\Translator;

readonly class CachedDeeplTranslator implements TranslatorInterface
{
    private const TARGET_LANG_MAP = [Locale::ENGLISH => 'en-US'];

    public function __construct(
        private Translator $deeplTranslator,
        private DeeplCacheStore $cacheStore,
        private TranslationUsedTracker $translationUsedTracker
    ) {
    }

    public function translate(string $text, string $locale, $dryRun = false): string
    {
        if (!$text) {
            return $text;
        }

        $byLine = explode("\n", $text);

        $self = $this;

        return implode("\n", \Arr::map($byLine, function ($line) use ($self, $locale, $dryRun) {
            try {
                return $self->translateSentence($line, $locale, $dryRun);
            } catch (\Throwable $e) {
                \Log::warning('Failed to translate the following by line: ' . $line . "\n$e");

                // Fall back to translating by sentence.
                return JapaneseSentenceSplitter::replaceCallback(
                    $line,
                    (static fn(array $match) => $self->translateSentence($match[0], $locale, $dryRun))
                );
            }
        }));
    }

    public function translateSentence(string $originalSentenceText, string $locale, bool $dryRun): string
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
            return DeeplMarkupTool::reassembleString($text, $translatedParts);
        }

        $characterCounter->addCharactersPassed($characterCount);

        $this->translationUsedTracker->add($text);

        $translated = $this->cacheStore->rememberForever(
            $text,
            $locale,
            function () use ($text, $locale, $dryRun, $characterCounter, $characterCount, $originalSentenceText) {
                $characterCounter->addCharactersSent($characterCount);

                if ($dryRun) {
                    echo "$originalSentenceText\n";
                    echo "$text\n";
                    echo "\n";
                }

                return $dryRun ?
                    null :
                    $this->deeplTranslator->translateText(
                        $text,
                        Locale::JAPANESE,
                        self::TARGET_LANG_MAP[$locale] ?? $locale,
                        [
                            TranslateTextOptions::FORMALITY => 'prefer_less',
                            TranslateTextOptions::TAG_HANDLING => 'xml',
                        ],
                    )->text;
            }
        );

        return DeeplMarkupTool::reassembleString($translated, $translatedParts);
    }
}
