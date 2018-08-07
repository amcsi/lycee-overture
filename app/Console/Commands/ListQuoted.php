<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Console\Commands;

use amcsi\LyceeOverture\CardTranslation;
use amcsi\LyceeOverture\I18n\JapaneseCharacterCounter;
use Illuminate\Console\Command;

class ListQuoted extends Command
{
    protected $description = 'Lists quoted text by frequency';
    protected $signature = 'lycee:list-quoted';

    public function handle()
    {
        $quoteds = [];
        foreach (CardTranslation::where('locale', 'en')->where('kanji_count', '>', '0')->get() as $cardTranslation) {
            preg_match_all('/[<"].*?[>"]/', $cardTranslation->ability_description, $matches, PREG_SET_ORDER);
            foreach ($matches as [$quoted]) {
                if (JapaneseCharacterCounter::countJapaneseCharacters($quoted)) {
                    $quoteds[] = $quoted;
                }
            }
        };

        $counts = array_count_values($quoteds);
        arsort($counts);
        $list = array_keys($counts);

        foreach ($list as $quoted) {
            // Repeat each subsentence the same amount of times as it occurs.
            for ($i = 0; $i < $counts[$quoted]; $i++) {
                echo "$quoted\n";
            }
        }
    }
}
