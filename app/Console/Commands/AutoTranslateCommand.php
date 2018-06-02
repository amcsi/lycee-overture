<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Console\Commands;

use amcsi\LyceeOverture\CardTranslation;
use amcsi\LyceeOverture\I18n\AutoTranslator;
use amcsi\LyceeOverture\I18n\Locale;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Attempts translations from Japanese description text based on patterns.
 */
class AutoTranslateCommand extends Command
{
    protected $signature = 'lycee:auto-translate';
    protected $description = 'Attempts translations from Japanese description text based on patterns.';

    public function handle()
    {
        $this->output->writeln('Starting auto translation of cards.');
        /** @var CardTranslation[] $japaneseCards */
        $japaneseCards = CardTranslation::where('locale', Locale::JAPANESE)->get();
        \Eloquent::unguard();

        $updatedNowThreshold = Carbon::now()->subSecond(3);

        $updatedCount = 0;
        foreach ($japaneseCards as $japaneseCard) {
            $englishCard = $japaneseCard->toArray();
            unset($englishCard['id'], $englishCard['created_at'], $englishCard['updated_at']);
            $englishCard['locale'] = Locale::ENGLISH;
            foreach (['ability_description'] as $key) {
                $englishCard[$key] = AutoTranslator::autoTranslate($japaneseCard->$key);
            }
            $englishCard = CardTranslation::updateOrCreate($englishCard);
            if ($englishCard->updated_at > $updatedNowThreshold) {
                ++$updatedCount;
            }
        }
        $this->output->writeln("Finished auto translation of cards. Updated: $updatedCount");
    }
}
