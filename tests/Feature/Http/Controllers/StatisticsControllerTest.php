<?php
declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use amcsi\LyceeOverture\CardTranslation;
use amcsi\LyceeOverture\I18n\Locale;
use amcsi\LyceeOverture\Suggestion;
use Tests\DatabaseTestCase;

class StatisticsControllerTest extends DatabaseTestCase
{
    public function testIndex()
    {
        $japanese = CardTranslation::factory()->create(['locale' => Locale::JAPANESE]);
        $card1 = $japanese->card;
        $japanese->kanji_count = 4;
        $japanese->save();
        $englishAuto = CardTranslation::factory()->for($japanese->card)->create(['locale' => Locale::ENGLISH_AUTO]);
        $englishAuto->kanji_count = 0;
        $englishAuto->save();

        $japanese = CardTranslation::factory()->create(['locale' => Locale::JAPANESE]);
        $card2 = $japanese->card;
        $japanese->kanji_count = 12;
        $japanese->save();
        $englishAuto = CardTranslation::factory()->for($japanese->card)->create(['locale' => Locale::ENGLISH_AUTO]);
        $englishAuto->kanji_count = 4;
        $englishAuto->save();

        $responseData = self::assertSuccessfulResponseData($this->getJson('/api/statistics'));
        $expectedStatistics = [
            'kanji_removal_ratio' => 0.75,
            'fully_translated_ratio' => 0.5,
            'translated_cards' => 1,
            'total_cards' => 2,
        ];
        self::assertSame($expectedStatistics, $responseData);

        Suggestion::factory()->for($card1)->create();

        $responseData = self::assertSuccessfulResponseData($this->getJson('/api/statistics'));
        self::assertSame($expectedStatistics, $responseData);

        Suggestion::factory()->for($card2)->create();
        $responseData = self::assertSuccessfulResponseData($this->getJson('/api/statistics'));
        self::assertSame([
            'kanji_removal_ratio' => 1,
            'fully_translated_ratio' => 1,
            'translated_cards' => 2,
            'total_cards' => 2,
        ], $responseData, 'Suggestions should count as fully translated.');
    }
}
