<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Console\Commands;

use amcsi\LyceeOverture\CardTranslation;
use amcsi\LyceeOverture\Debug\Profiling;
use amcsi\LyceeOverture\I18n\DeeplTranslator\DeeplTranslatorLastUsedUpdater;
use amcsi\LyceeOverture\I18n\JapaneseCharacterCounter;
use amcsi\LyceeOverture\I18n\Locale;
use amcsi\LyceeOverture\I18n\NameTranslator\CachedDeeplTranslator;
use amcsi\LyceeOverture\I18n\NameTranslator\NameTranslator;
use amcsi\LyceeOverture\I18n\TranslationUsedTracker;
use Carbon\Carbon;
use Eloquent;
use GuzzleHttp\Psr7\Utils;
use Illuminate\Console\Command;
use Illuminate\Database\Query\Builder;
use LogicException;
use RuntimeException;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Stopwatch\Stopwatch;

class DeeplTranslateCommand extends Command
{
    public const COMMAND = 'lycee:deepl-translate';

    protected $signature = self::COMMAND . ' {--dump-to-file} {--dry-run}  {--limit-translation-sends=0}';
    protected $description = 'Attempts translations from Japanese description text with DeepL.';


    public function handle(
        CardTranslation $cardTranslation,
        NameTranslator $nameTranslator,
        CachedDeeplTranslator $cachedDeeplTranslator,
    ) {
        $stopwatchEvent = (new Stopwatch())->start('deepl-translate-command');
        $this->output->writeln('Starting DeepL translation of cards.');
        /** @var Builder $japaneseBuilder */
        $japaneseBuilder = $cardTranslation->newQuery()->where('locale', Locale::JAPANESE);
        /** @var CardTranslation[] $japaneseCards */
        $japaneseCards = $japaneseBuilder->get();

        $fileDumpPath = sprintf(storage_path('dump/deeplTranslate/%s.txt'), date('Y-m-d--His'));
        $fileDumpDir = dirname($fileDumpPath);
        if (!is_dir($fileDumpDir) && !@mkdir($fileDumpDir, 0775, true) && !is_dir($fileDumpDir)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $fileDumpDir));
        }
        $dumpFile = $this->option('dump-to-file') ?
            Utils::tryFopen($fileDumpPath, 'wb') :
            null;
        $propertiesToDump = ['card_id', ...CardTranslation::NAME_COLUMNS, ...CardTranslation::TEXT_COLUMNS];

        $locale = Locale::ENGLISH_DEEPL;
        $englishCards = $cardTranslation->newQuery()
            ->where('locale', $locale)
            ->get()
            ->keyBy(
                function (
                    CardTranslation $cardTranslation
                ) {
                    return $cardTranslation->card_id;
                }
            );

        // By default the english japanese character count is the same as the Japanese (untranslated).
        $cardCount = $japaneseBuilder->count();

        Eloquent::unguard();

        $progressBar = new ProgressBar($this->output, $cardCount, 1);
        $progressBar->start();

        $updatedNowThreshold = Carbon::now()->subSeconds(3);

        $updatedCount = 0;

        $dryRun = (bool) $this->option('dry-run');
        $limitTranslationSends = (int) $this->option('limit-translation-sends');

        $translationsUsedTracker = app(TranslationUsedTracker::class);
        $characterCounter = $translationsUsedTracker->getCharacterCounter();

        // Iterate each Japanese card to create the English variant.
        // We must make sure to only auto translate those properties which have not been manually translated.
        // We must also make sure to copy all non-auto-translatable properties from Japanese,
        // but only ones that haven't been manually translated.
        foreach ($japaneseCards as $japaneseCard) {
            $cardId = $japaneseCard->card_id;
            $englishCard = $englishCards->get($cardId) ?
                // Update based on the existing English card data.
                $englishCards[$cardId] :
                // Create a new English card based on the Japanese one.
                $japaneseCard->replicate()->setAttribute('locale', $locale);

            // Iterate the auto-translatable fields.
            foreach (CardTranslation::TEXT_COLUMNS as $key) {
                try {
                    $translation = $cachedDeeplTranslator->translate(
                        $japaneseCard->$key,
                        Locale::ENGLISH, $dryRun
                    );
                    $englishCard[$key] = $translation;
                } catch (LogicException $e) {
                    $this->output->warning(
                        $englishCard['card_id'] . ' - ' . $japaneseCard->$key . ': ' . $e->getMessage()
                    );
                    // Retain the original Japanese text in case of an exception.
                    $englishCard[$key] = $japaneseCard->$key;
                }
            }

            // Manual translations for names, types etc.
            $englishCard['name'] = $nameTranslator->tryTranslateName($japaneseCard['name'], true);
            $englishCard['ability_name'] = $nameTranslator->tryTranslateName($japaneseCard['ability_name']);
            $englishCard['character_type'] = $nameTranslator->tryTranslateCharacterType(
                $japaneseCard['character_type']
            );

            $englishCard['kanji_count'] = JapaneseCharacterCounter::countJapaneseCharactersForDbRow(
                $englishCard->toArray()
            );

            if (!$dryRun) {
                $englishCard->save();
            }

            if ($englishCard->updated_at > $updatedNowThreshold) {
                ++$updatedCount;
            }

            if ($dumpFile) {
                foreach ($propertiesToDump as $property) {
                    fwrite($dumpFile, "$property: $englishCard[$property]\n");
                }
                fwrite($dumpFile, "\n");
            }

            $progressBar->advance();

            if ($limitTranslationSends && $characterCounter->translationsSent >= $limitTranslationSends) {
                $this->warn('Translation limit reached.');
                break;
            }
        }
        $progressBar->clear();

        $this->output->writeln("Finished DeepL translation of cards. Updated: $updatedCount");

        $charactersSentColor = $characterCounter->charactersSent > 0 && !$dryRun ? 'yellow' : 'green';
        $messages[] = "Characters sent: <fg=$charactersSentColor>$characterCounter->charactersSent</>";
        $messages[] = 'Characters passed: ' . $characterCounter->charactersPassed;
        $messages[] = 'Characters attempted: ' . $characterCounter->charactersAttempted;
        $messages[] = 'Translations sent: ' . $characterCounter->translationsSent;
        $messages[] = 'Translations passed: ' . $characterCounter->translationsPassed;
        $messages[] = 'Translations attempted: ' . $characterCounter->translationsAttempted;

        $this->output->writeln($messages);

        if (!$dryRun) {
            app(DeeplTranslatorLastUsedUpdater::class)->updateLastUsed();
        }

        $this->output->text(
            "Finished DeepL translation of cards in " . Profiling::stopwatchToHuman($stopwatchEvent->stop())
        );
    }
}
