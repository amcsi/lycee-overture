<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Console\Commands;

use amcsi\LyceeOverture\Models\CardTranslation;
use amcsi\LyceeOverture\I18n\JapaneseCharacterCounter;
use Illuminate\Console\Command;

class ListKanjiSubsentences extends Command
{
    protected $description = 'Lists subsentences containing japanese characters';
    protected $signature = 'lycee:list-kanji-subsentences {--order-by-frequency}';

    public function handle()
    {
        $subsentences = [];
        foreach (CardTranslation::where('locale', 'en')->where('kanji_count', '>', '0')->get() as $cardTranslation) {
            foreach (AutoTranslateCommand::AUTO_TRANSLATE_FIELDS as $field) {
                foreach (preg_split('/[.,] ?/', $cardTranslation->{$field}) as $subsentence) {
                    if (JapaneseCharacterCounter::countJapaneseCharacters($subsentence)) {
                        $subsentences[] = $subsentence;
                    }
                }
            }
        };

        if (!$this->option('order-by-frequency')) {
            // Order sorted.
            sort($subsentences);
            foreach ($subsentences as $subsentence) {
                echo "$subsentence\n";
            }
            return;
        }

        $counts = array_count_values($subsentences);
        arsort($counts);
        $list = array_keys($counts);

        foreach ($list as $subsentence) {
            // Repeat each subsentence the same amount of times as it occurs.
            for ($i = 0; $i < $counts[$subsentence]; $i++) {
                echo "$subsentence\n";
            }
        }
    }
}
