<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Console\Commands;

use amcsi\LyceeOverture\CardTranslation;
use amcsi\LyceeOverture\Debug\Profiling;
use amcsi\LyceeOverture\I18n\JapaneseCharacterCounter;
use amcsi\LyceeOverture\I18n\NameTranslator\NameTranslator;
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

        $typesFromDb = CardTranslation::where('locale', 'ja')
            ->select(['character_type', 'name', 'ability_name'])->get();

        $textsByTextType = ['character_type' => [], 'name' => [], 'ability_name' => []];

        foreach ($typesFromDb as $typeFromDb) {
            foreach ($textsByTextType as $textType => $_) {
                /** @noinspection PhpVariableVariableInspection */
                $text = $typeFromDb->$textType;
                // Upload each (punctuation) component of the text separately.
                NameTranslator::doSeparatedByPunctuation(
                    $text,
                    static function (string $part) use (&$textsByTextType, &$textType) {
                        if (JapaneseCharacterCounter::countJapaneseCharacters($part)) {
                            $textsByTextType[$textType][$part] = $part;
                        }
                    }
                );
            }
        }

        app(OneSkyClient::class)->uploadNamesAndTypes(
            $textsByTextType['character_type'],
            $textsByTextType['name'],
            $textsByTextType['ability_name']
        );

        $this->output->text(
            "Finished uploading translations to OneSky in " . Profiling::stopwatchToHuman($stopwatchEvent->stop())
        );
    }
}
