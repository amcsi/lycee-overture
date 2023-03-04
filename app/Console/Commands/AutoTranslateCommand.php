<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Console\Commands;

use amcsi\LyceeOverture\CardTranslation;
use amcsi\LyceeOverture\Debug\Profiling;
use amcsi\LyceeOverture\I18n\AutoTranslator;
use amcsi\LyceeOverture\I18n\AutoTranslator\NameSyncer;
use amcsi\LyceeOverture\I18n\CommentTranslator\CommentTranslator;
use amcsi\LyceeOverture\I18n\CommentTranslator\PreCommentTranslator;
use amcsi\LyceeOverture\I18n\DeeplTranslator\DeeplTranslatorLastUsedUpdater;
use amcsi\LyceeOverture\I18n\JapaneseCharacterCounter;
use amcsi\LyceeOverture\I18n\Locale;
use amcsi\LyceeOverture\I18n\NameTranslator\NameTranslator;
use amcsi\LyceeOverture\I18n\Statistics\TranslationCoverageChecker;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Query\Builder;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Stopwatch\Stopwatch;
use function GuzzleHttp\Psr7\try_fopen;

/**
 * Attempts translations from Japanese description text based on patterns.
 */
class AutoTranslateCommand extends Command
{
    public const COMMAND = 'lycee:auto-translate';
    public const AUTO_TRANSLATE_FIELDS = [
        'basic_abilities',
        'pre_comments',
        'ability_description',
        'ability_cost',
        'comments',
    ];

    protected $signature = self::COMMAND . ' {--dump-to-file}';
    protected $description = 'Attempts translations from Japanese description text based on patterns.';

    public function handle(
        CardTranslation $cardTranslation,
        TranslationCoverageChecker $translationCoverageChecker,
        AutoTranslator $autoTranslator,
        NameTranslator $nameTranslator,
        CommentTranslator $commentTranslator,
        PreCommentTranslator $preCommentTranslator
    ) {
        $stopwatchEvent = (new Stopwatch())->start('auto-translate-command');
        $this->output->writeln('Starting auto translation of cards.');
        /** @var Builder $japaneseBuilder */
        $japaneseBuilder = $cardTranslation->newQuery()->where('locale', Locale::JAPANESE);
        /** @var CardTranslation[] $japaneseCards */
        $japaneseCards = $japaneseBuilder->get();

        $fileDumpPath = sprintf(storage_path('dump/autoTranslate/%s.txt'), date('Y-m-d--His'));
        $fileDumpDir = dirname($fileDumpPath);
        if (!is_dir($fileDumpDir) && !@mkdir($fileDumpDir, 0775, true) && !is_dir($fileDumpDir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $fileDumpDir));
        }
        $dumpFile = $this->option('dump-to-file') ?
            try_fopen($fileDumpPath, 'wb') :
            null;
        $propertiesToDump = ['card_id', 'name', 'ability_name', 'character_type', ...self::AUTO_TRANSLATE_FIELDS];

        $locale = Locale::ENGLISH . '-auto';
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

        $beforeEnglishKanjiRemovalRatio = $translationCoverageChecker->calculateRatioOfJapaneseCharacterRemoval();
        $beforeEnglishFullTranslationRatio = $translationCoverageChecker->calculateRatioOfFullyTranslated();
        $beforeEnglishFullTranslationCount = $translationCoverageChecker->countFullyTranslated();

        \Eloquent::unguard();

        $progressBar = new ProgressBar($this->output, $cardCount, 1);
        $progressBar->start();

        $updatedNowThreshold = Carbon::now()->subSecond(3);

        $updatedCount = 0;

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
            foreach (self::AUTO_TRANSLATE_FIELDS as $key) {
                try {
                    // TODO: make sure manual translation of auto-translatable fields do not get overwritten.
                    $englishCard[$key] = $autoTranslator->autoTranslate($japaneseCard->$key);
                } catch (\LogicException $e) {
                    $this->output->warning(
                        $englishCard['card_id'] . ' - ' . $japaneseCard->$key . ': ' . $e->getMessage()
                    );
                    // Retain the original Japanese text in case of an exception.
                    $englishCard[$key] = $japaneseCard->$key;
                }
            }

            // Manual translations for names, types etc.
            $englishCard['name'] = $nameTranslator->tryTranslateName($japaneseCard['name'], true);
            $englishCard['pre_comments'] = $preCommentTranslator->translate($japaneseCard['pre_comments']);
            $englishCard['ability_name'] = $nameTranslator->tryTranslateName($japaneseCard['ability_name']);
            $englishCard['character_type'] = $nameTranslator->tryTranslateCharacterType(
                $japaneseCard['character_type']
            );
            $englishCard['comments'] = $commentTranslator->translate($japaneseCard['comments']);

            $englishCard['kanji_count'] = JapaneseCharacterCounter::countJapaneseCharactersForDbRow(
                $englishCard->toArray()
            );

            $englishCard->save();

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
        }
        $progressBar->clear();

        app(DeeplTranslatorLastUsedUpdater::class)->updateLastUsed();

        $this->output->writeln("Finished auto translation of cards. Updated: $updatedCount");

        // Report on translations with kanji count removal percentage as translation percentage.

        $afterEnglishKanjiRemovalRatio = $translationCoverageChecker->calculateRatioOfJapaneseCharacterRemoval();
        $afterEnglishFullTranslationRatio = $translationCoverageChecker->calculateRatioOfFullyTranslated();
        $afterEnglishFullTranslationCount = $translationCoverageChecker->countFullyTranslated();

        $oldTranslationPercent = $beforeEnglishKanjiRemovalRatio * 100;
        $newTranslationPercent = $afterEnglishKanjiRemovalRatio * 100;

        $oldPercentText = sprintf('%.3f%%', $oldTranslationPercent);
        $newPercentText = sprintf('%.3f%%', $newTranslationPercent);

        $compared = $newTranslationPercent <=> $oldTranslationPercent;

        // Color in green/red depending on which is better.
        if ($compared > 0) {
            $newPercentText = "<fg=green>$newPercentText</>";
            $oldPercentText = "<fg=red>$oldPercentText</>";
        } elseif ($compared < 0) {
            $oldPercentText = "<fg=green>$oldPercentText</>";
            $newPercentText = "<fg=red>$newPercentText</>";
        }

        $this->output->writeln(
            sprintf(
                "Auto translation description: %s => %s",
                $oldPercentText,
                $newPercentText
            )
        );

        // Report on full translation coverage.

        $oldTranslationPercent = $beforeEnglishFullTranslationRatio * 100;
        $newTranslationPercent = $afterEnglishFullTranslationRatio * 100;

        $oldPercentText = sprintf('%.3f%% (%d)', $oldTranslationPercent, $beforeEnglishFullTranslationCount);
        $newPercentText = sprintf('%.3f%% (%d)', $newTranslationPercent, $afterEnglishFullTranslationCount);

        $compared = $newTranslationPercent <=> $oldTranslationPercent;

        // Color in green/red depending on which is better.
        if ($compared > 0) {
            $newPercentText = "<fg=green>$newPercentText</>";
            $oldPercentText = "<fg=red>$oldPercentText</>";
        } elseif ($compared < 0) {
            $oldPercentText = "<fg=green>$oldPercentText</>";
            $newPercentText = "<fg=red>$newPercentText</>";
        }

        $this->output->writeln(
            sprintf(
                "Full translation: %s => %s out of (%d)",
                $oldPercentText,
                $newPercentText,
                $cardCount
            )
        );

        NameSyncer::syncNames();
        $this->output->text('Synced card names.');

        $this->output->text(
            "Finished auto translation of cards in " . Profiling::stopwatchToHuman($stopwatchEvent->stop())
        );
    }
}
