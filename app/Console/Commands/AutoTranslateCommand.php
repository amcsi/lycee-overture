<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Console\Commands;

use amcsi\LyceeOverture\CardTranslation;
use amcsi\LyceeOverture\I18n\AutoTranslator;
use amcsi\LyceeOverture\I18n\Locale;
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
        foreach ($japaneseCards as $japaneseCard) {
            $englishCard = $japaneseCard->toArray();
            unset($englishCard['id']);
            $englishCard['locale'] = Locale::ENGLISH;
            foreach (['ability_description'] as $key) {
                $englishCard[$key] = AutoTranslator::autoTranslate($japaneseCard->$key);
            }
            CardTranslation::updateOrCreate($englishCard);
        }
        $this->output->writeln('Finished auto translation of cards.');
    }
}
