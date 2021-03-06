<?php
declare(strict_types=1);

namespace Tests\Feature\I18n\Statistics;

use amcsi\LyceeOverture\Card;
use amcsi\LyceeOverture\CardTranslation;
use amcsi\LyceeOverture\Etc\Statistics;
use amcsi\LyceeOverture\Etc\StatisticsResource;
use amcsi\LyceeOverture\I18n\Locale;
use amcsi\LyceeOverture\I18n\Statistics\TranslationCoverageChecker;
use Tests\DatabaseTestCase;

class TranslationCoverageCheckerTest extends DatabaseTestCase
{
    private $cardTranslation1Ja;
    private $cardTranslation1EnAuto;
    private $cardTranslation2Ja;
    private $cardTranslation2EnAuto;
    private $cardTranslation2En;

    public function setUp(): void
    {
        parent::setUp();

        $card1 = Card::factory()->create(['id' => 'LO-0001']);
        $card2 = Card::factory()->create(['id' => 'LO-0002']);

        $defaultProperties = [
            'name' => '',
            'basic_abilities' => '',
            'ability_name' => '',
            'ability_cost' => '',
            'ability_description' => '',
            'comments' => '',
        ];
        $this->cardTranslation1Ja = CardTranslation::forceCreate(array_replace(
            $defaultProperties,
            ['locale' => Locale::JAPANESE, 'kanji_count' => 10, 'card_id' => $card1->id]
        ));
        $this->cardTranslation1EnAuto = CardTranslation::forceCreate(array_replace(
            $defaultProperties,
            ['locale' => Locale::ENGLISH_AUTO, 'kanji_count' => 6, 'card_id' => $card1->id]
        ));
        $this->cardTranslation2Ja = CardTranslation::forceCreate(array_replace(
            $defaultProperties,
            ['locale' => Locale::JAPANESE, 'kanji_count' => 20, 'card_id' => $card2->id]
        ));
        $this->cardTranslation2EnAuto = CardTranslation::forceCreate(array_replace(
            $defaultProperties,
            ['locale' => Locale::ENGLISH_AUTO, 'kanji_count' => 10, 'card_id' => $card2->id]
        ));
        $this->cardTranslation2En = CardTranslation::forceCreate(array_replace(
            $defaultProperties,
            ['locale' => Locale::ENGLISH, 'kanji_count' => 0, 'card_id' => $card2->id]
        ));
    }

    public function testEmptyStatistics(): void
    {
        CardTranslation::query()->delete();
        Card::query()->delete();
        $statistics = app(TranslationCoverageChecker::class)->calculateStatistics([]);
        self::assertStatistics($statistics, 0.0, 0.0, 0, 0);
    }

    private static function assertStatistics(
        Statistics $statistics,
        float $kanjiRemovalRatio,
        float $fullyTranslatedRatio,
        int $translatedCards,
        int $totalCards,
        string $message = ''
    ): void {
        self::assertSame([
            'kanji_removal_ratio' => $kanjiRemovalRatio,
            'fully_translated_ratio' => $fullyTranslatedRatio,
            'translated_cards' => $translatedCards,
            'total_cards' => $totalCards,
        ],
            (new StatisticsResource($statistics))->toArray(null),
            $message);
    }

    public function testNoEnglishTranslations(): void
    {
        CardTranslation::whereIn('locale', [Locale::ENGLISH, Locale::ENGLISH_AUTO])->delete();
        $statistics = app(TranslationCoverageChecker::class)->calculateStatistics([]);
        // Total cards should be 0, because when calculating statistics of cards when searching (text),
        // those filters would need to be applied to the English version of cards.
        self::assertStatistics($statistics, 0.0, 0.0, 0, 0);
    }

    public function testGlobalStatistics(): void
    {
        $statistics = app(TranslationCoverageChecker::class)->calculateStatistics([]);
        self::assertStatistics($statistics, 1 - 6 / 30, 0.5, 1, 2);
    }

    public function testFullyTranslated(): void
    {
        $this->cardTranslation1EnAuto->kanji_count = 0;
        $this->cardTranslation1EnAuto->save();
        $statistics = app(TranslationCoverageChecker::class)->calculateStatistics([]);
        self::assertStatistics($statistics, 1, 1, 2, 2);
    }

    public function testWithSearch(): void
    {
        $this->cardTranslation1EnAuto->name = 'Not fully translated';
        $this->cardTranslation1EnAuto->save();
        $statistics = app(TranslationCoverageChecker::class)->calculateStatistics(['name' => 'Not fully translated']);
        self::assertStatistics($statistics, 1 - 6 / 10, 0, 0, 1);
    }

    public function testFullyTranslatedWithSearch(): void
    {
        $this->cardTranslation2En->name = 'Bob';
        $this->cardTranslation2En->save();
        $statistics = app(TranslationCoverageChecker::class)->calculateStatistics(['name' => 'Bob']);
        self::assertStatistics($statistics, 1, 1, 1, 1);
        $en2Auto = $this->cardTranslation2EnAuto;
        $newEn2Auto = $en2Auto->replicate();
        $en2Auto->delete();
        $newEn2Auto->save();
        self::assertStatistics(
            $statistics,
            1,
            1,
            1,
            1,
            'The statistics should stay the same even if the order changes'
        );
    }
}
