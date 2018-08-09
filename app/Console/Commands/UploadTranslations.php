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
    public const COMMAND = 'lycee:upload-translations';

    protected $signature = self::COMMAND;
    protected $description = 'Uploads translatables to OneSky';

    public function handle()
    {
        $stopwatchEvent = (new Stopwatch())->start('upload-translations');
        $this->output->text('Started uploading translations to OneSky');

        $typesFromDb = CardTranslation::where('locale', 'jp')
            ->select(['character_type', 'name', 'ability_name'])->get();
        $types = [];
        $names = [];
        $abilityNames = [];
        foreach ($typesFromDb as $typeFromDb) {
            $type = $typeFromDb->character_type;
            $name = $typeFromDb->name;
            $abilityName = $typeFromDb->ability_name;
            if (JapaneseCharacterCounter::countJapaneseCharacters($type)) {
                $types[$type] = $type;
            }
            if (JapaneseCharacterCounter::countJapaneseCharacters($name)) {
                $names[$name] = $name;
            }
            if (JapaneseCharacterCounter::countJapaneseCharacters($abilityName)) {
                $abilityNames[$abilityName] = $abilityName;
            }
        }

        app(OneSkyClient::class)->uploadNamesAndTypes($types, $names, $abilityNames);

        $this->output->text(
            "Finished uploading translations to OneSky in " . Profiling::stopwatchToHuman($stopwatchEvent->stop())
        );
    }
}
