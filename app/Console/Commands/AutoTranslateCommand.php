<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Console\Commands;

use amcsi\LyceeOverture\CardTranslation;
use amcsi\LyceeOverture\I18n\AutoTranslator;
use amcsi\LyceeOverture\I18n\JapaneseCharacterCounter;
use amcsi\LyceeOverture\I18n\Locale;
use amcsi\LyceeOverture\I18n\Statistics\TranslationCoverageChecker;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Query\Builder;

/**
 * Attempts translations from Japanese description text based on patterns.
 */
class AutoTranslateCommand extends Command
{
    public const COMMAND = 'lycee:auto-translate';
    public const AUTO_TRANSLATE_FIELDS = ['ability_description', 'basic_abilities'];

    protected $signature = self::COMMAND;

    protected $description = 'Attempts translations from Japanese description text based on patterns.';

    public function handle(CardTranslation $cardTranslation, TranslationCoverageChecker $translationCoverageChecker)
    {
        $this->output->writeln('Starting auto translation of cards.');
        /** @var Builder $japaneseBuilder */
        $japaneseBuilder = $cardTranslation->newQuery()->where('locale', Locale::JAPANESE);
        /** @var CardTranslation[] $japaneseCards */
        $japaneseCards = $japaneseBuilder->get();

        // By default the english japanese character count is the same as the Japanese (untranslated).
        $cardCount = $japaneseBuilder->count();

        $beforeEnglishKanjiRemovalRatio = $translationCoverageChecker->calculateRatioOfJapaneseCharacterRemoval();
        $beforeEnglishFullTranslationRatio = $translationCoverageChecker->calculateRatioOfFullyTranslated();
        $beforeEnglishFullTranslationCount = $translationCoverageChecker->countFullyTranslated();

        \Eloquent::unguard();

        $updatedNowThreshold = Carbon::now()->subSecond(3);

        $updatedCount = 0;
        foreach ($japaneseCards as $japaneseCard) {
            $englishCard = $japaneseCard->toArray();
            unset($englishCard['id'], $englishCard['created_at'], $englishCard['updated_at']);
            $englishCard['locale'] = Locale::ENGLISH;
            foreach (self::AUTO_TRANSLATE_FIELDS as $key) {
                try {
                    $englishCard[$key] = AutoTranslator::autoTranslate($japaneseCard->$key);
                } catch (\LogicException $e) {
                    $this->output->warning(
                        $englishCard['card_id'] . ' - ' . $japaneseCard->$key . ': ' . $e->getMessage()
                    );
                    // Retain the original Japanese text in case of an exception.
                    $englishCard[$key] = $japaneseCard->$key;
                }
                $englishCard['kanji_count'] = JapaneseCharacterCounter::countJapaneseCharactersForDbRow($englishCard);
            }
            $englishCard = CardTranslation::updateOrCreate([
                'card_id' => $englishCard['card_id'],
                'locale' => $englishCard['locale'],
            ], $englishCard);
            if ($englishCard->updated_at > $updatedNowThreshold) {
                ++$updatedCount;
            }
        }

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
    }
}
