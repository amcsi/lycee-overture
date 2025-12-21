<?php
declare(strict_types=1);

namespace Database\Factories;

use amcsi\LyceeOverture\Models\Card;
use amcsi\LyceeOverture\Models\CardTranslation;
use amcsi\LyceeOverture\I18n\JapaneseCharacterCounter;
use amcsi\LyceeOverture\I18n\Locale;
use Illuminate\Database\Eloquent\Factories\Factory;

class CardTranslationFactory extends Factory
{
    protected $model = CardTranslation::class;

    public function configure()
    {
        return $this->afterMaking(function (CardTranslation $translation) {
            $translation->kanji_count = JapaneseCharacterCounter::countJapaneseCharactersForDbRow(
                $translation->toArray()
            );
        });
    }

    public function definition()
    {
        return array_replace([
            'card_id' => Card::factory(),
            'locale' => Locale::JAPANESE,
            'name' => app('japaneseFaker')->name,
            'ability_name' => app('japaneseFaker')->name,
        ],
            app(FactoryCommon::class)->getSuggestableTranslatableProperties());
    }
}
