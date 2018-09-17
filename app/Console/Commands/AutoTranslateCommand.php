<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Console\Commands;

use amcsi\LyceeOverture\CardTranslation;
use amcsi\LyceeOverture\Debug\Profiling;
use amcsi\LyceeOverture\I18n\AutoTranslator;
use amcsi\LyceeOverture\I18n\JapaneseCharacterCounter;
use amcsi\LyceeOverture\I18n\Locale;
use amcsi\LyceeOverture\I18n\NameTranslator\NameTranslator;
use amcsi\LyceeOverture\I18n\Statistics\TranslationCoverageChecker;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Query\Builder;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Attempts translations from Japanese description text based on patterns.
 */
class AutoTranslateCommand extends Command
{
    public const COMMAND = 'lycee:auto-translate';
    public const AUTO_TRANSLATE_FIELDS = ['ability_description', 'ability_cost', 'basic_abilities', 'comments'];

    protected $signature = self::COMMAND;
    protected $description = 'Attempts translations from Japanese description text based on patterns.';

    public function handle(
        CardTranslation $cardTranslation,
        TranslationCoverageChecker $translationCoverageChecker,
        AutoTranslator $autoTranslator,
        NameTranslator $nameTranslator
    ) {
        $stopwatchEvent = (new Stopwatch())->start('auto-translate-command');
        $this->output->writeln('Starting auto translation of cards.');
        /** @var Builder $japaneseBuilder */
        $japaneseBuilder = $cardTranslation->newQuery()->where('locale', Locale::JAPANESE);
        /** @var CardTranslation[] $japaneseCards */
        $japaneseCards = $japaneseBuilder->get();

        $englishCards = $cardTranslation->newQuery()
            ->where('locale', Locale::ENGLISH)
            ->get()
            ->keyBy(function (
                CardTranslation $cardTranslation
            ) {
                return $cardTranslation->card_id;
            });

        // By default the english japanese character count is the same as the Japanese (untranslated).
        $cardCount = $japaneseBuilder->count();

        $beforeEnglishKanjiRemovalRatio = $translationCoverageChecker->calculateRatioOfJapaneseCharacterRemoval();
        $beforeEnglishFullTranslationRatio = $translationCoverageChecker->calculateRatioOfFullyTranslated();
        $beforeEnglishFullTranslationCount = $translationCoverageChecker->countFullyTranslated();

        \Eloquent::unguard();

        $updatedNowThreshold = Carbon::now()->subSecond(3);

        $updatedCount = 0;

        // Iterate each Japanese card to create the English variant.
        // We must make sure to only auto translate those properties which have not been manually translated.
        // We must also make sure to copy all non-auto-translatable properties from Japanese,
        // but only ones that haven't been manually translated.
        foreach ($japaneseCards as $japaneseCard) {
            $cardId = $japaneseCard->card_id;
            $japaneseCardAsArray = $japaneseCard->toArray();
            $englishCard = $englishCards->get($cardId) ?
                // Update based on the existing English card data.
                $englishCards->get($cardId)->toArray() :
                // Work with the Japanese card then.
                $japaneseCardAsArray;

            // Fill in only missing values. This ensures anything manually translated would not get overwritten, but
            // new properties would get copied over.
            foreach ($japaneseCardAsArray as $field => $value) {
                if (empty($englishCard[$field])) {
                    $englishCard[$field] = $value;
                }
            }

            // Strip these properties.
            unset($englishCard['id'], $englishCard['created_at'], $englishCard['updated_at']);

            $englishCard['locale'] = Locale::ENGLISH;
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
                // Manual translations for names, types etc.
                $englishCard['name'] = $nameTranslator->tryTranslateName($japaneseCard['name']);
                $englishCard['ability_name'] = $nameTranslator->tryTranslateName($japaneseCard['ability_name']);
                $englishCard['character_type'] = $nameTranslator->tryTranslateCharacterType(
                    $japaneseCard['character_type']
                );

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

        $this->output->text(
            "Finished auto translation of cards in " . Profiling::stopwatchToHuman($stopwatchEvent->stop())
        );
    }
}
