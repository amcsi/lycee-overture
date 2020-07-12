<?php
declare(strict_types=1);

use amcsi\LyceeOverture\CardTranslation;
use amcsi\LyceeOverture\I18n\JapaneseCharacterCounter;
use amcsi\LyceeOverture\I18n\Locale;
use Illuminate\Database\Migrations\Migration;

class RecalculateManuallyTranslatedKanjiCounts extends Migration
{
    public function up()
    {
        \DB::transaction(function () {
            foreach (CardTranslation::whereLocale(Locale::ENGLISH)->cursor() as $cardTranslation) {
                $cardTranslation->kanji_count = JapaneseCharacterCounter::countJapaneseCharactersForDbRow($cardTranslation);
                $cardTranslation->save();
            }
        });
    }

    public function down()
    {
        //
    }
}
