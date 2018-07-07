<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Console\Commands;

use amcsi\LyceeOverture\CardTranslation;
use amcsi\LyceeOverture\I18n\AutoTranslator;
use amcsi\LyceeOverture\I18n\JapaneseCharacterCounter;
use amcsi\LyceeOverture\I18n\Locale;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Query\Builder;

/**
 * Attempts translations from Japanese description text based on patterns.
 */
class AutoTranslateCommand extends Command
{
    public const AUTO_TRANSLATE_FIELDS = ['ability_description'];

    protected $signature = 'lycee:auto-translate';
    protected $description = 'Attempts translations from Japanese description text based on patterns.';

    public function handle()
    {
        $this->output->writeln('Starting auto translation of cards.');
        /** @var Builder $japaneseBuilder */
        $japaneseBuilder = CardTranslation::where('locale', Locale::JAPANESE);
        /** @var Builder $englishBuilder */
        $englishBuilder = CardTranslation::where('locale', Locale::ENGLISH);
        /** @var CardTranslation[] $japaneseCards */
        $japaneseCards = $japaneseBuilder->get();

        $japaneseKanjiCount = $japaneseBuilder->sum('kanji_count') ?: 0;
        // By default the english japanese character count is the same as the Japanese (untranslated).
        $englishKanjiCount = $englishBuilder->sum('kanji_count') ?: $japaneseKanjiCount;

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

        $afterEnglishKanjiCount = $englishBuilder->sum('kanji_count') ?: 0;

        $oldTranslationPercent = 100 - ($englishKanjiCount * 100) / $japaneseKanjiCount;
        $newTranslationPercent = 100 - ($afterEnglishKanjiCount * 100) / $japaneseKanjiCount;

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
                "Auto translation: %s => %s",
                $oldPercentText,
                $newPercentText
            )
        );
    }
}
