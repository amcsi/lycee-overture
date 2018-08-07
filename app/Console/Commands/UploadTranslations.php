<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Console\Commands;

use amcsi\LyceeOverture\CardTranslation;
use amcsi\LyceeOverture\Debug\Profiling;
use amcsi\LyceeOverture\I18n\JapaneseCharacterCounter;
use amcsi\LyceeOverture\I18n\OneSkyClient;
use Illuminate\Console\Command;
use Symfony\Component\Stopwatch\Stopwatch;

class UploadTranslations extends Command
{
    protected $signature = 'lycee:upload-translations';
    protected $description = 'Uploads translatables to OneSky';

    public function handle()
    {
        $stopwatchEvent = (new Stopwatch())->start('upload-translations');
        $this->output->text('Started uploading translations to OneSky');

        $typesFromDb = CardTranslation::where('locale', 'jp')
            ->where('character_type', '!=', '')
            ->select('character_type')->get();
        $types = [];
        foreach ($typesFromDb as $typeFromDb) {
            $type = $typeFromDb->character_type;
            if (!JapaneseCharacterCounter::countJapaneseCharacters($type)) {
                // No Japanese characters; no need to translate.
                continue;
            }
            if (isset($types[$type])) {
                continue;
            }
            $types[$type] = true;
        }

        app(OneSkyClient::class)->uploadCharacterTypes(array_keys($types));

        $this->output->text(
            "Finished uploading translations to OneSky in " . Profiling::stopwatchToHuman($stopwatchEvent->stop())
        );
    }
}
