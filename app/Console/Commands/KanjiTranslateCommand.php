<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Console\Commands;

use amcsi\LyceeOverture\CardTranslation;
use amcsi\LyceeOverture\Debug\Profiling;
use amcsi\LyceeOverture\I18n\Locale;
use amcsi\LyceeOverture\I18n\NameTranslator\KanjiTranslator;
use Illuminate\Console\Command;
use Symfony\Component\Stopwatch\Stopwatch;

class KanjiTranslateCommand extends Command
{
    public const COMMAND = 'lycee:kanji-translate';

    protected $signature = self::COMMAND;
    protected $description = "Attempts translations from Japanese kanji names with Yahoo's API.";

    public function handle(KanjiTranslator $kanjiTranslator)
    {
        $stopwatchEvent = (new Stopwatch())->start('kanji-translate-command');
        $this->output->writeln('Starting kanji translation of card names.');

        $englishCards = CardTranslation::where('locale', Locale::ENGLISH)->cursor();

        $translatedNames = 0;
        $totalNames = 0;

        foreach ($englishCards as $englishCard) {
            $oldName = $englishCard->name;
            $englishCard->name = $kanjiTranslator->translate($oldName);
            if ($englishCard->name !== $oldName) {
                $this->output->text(sprintf('Translated `%s` to `%s`', $oldName, $englishCard->name));
                ++$translatedNames;
            }
            ++$totalNames;
        }

        $this->output->text(
            sprintf('Translated: %d/%d', $translatedNames, $totalNames)
        );

        $this->output->text(
            'Finished kanji translation of card names in ' . Profiling::stopwatchToHuman($stopwatchEvent->stop())
        );
    }
}
