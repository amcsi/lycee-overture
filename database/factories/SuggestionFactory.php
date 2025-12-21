<?php
declare(strict_types=1);

namespace Database\Factories;

use amcsi\LyceeOverture\Models\Card;
use amcsi\LyceeOverture\I18n\Locale;
use amcsi\LyceeOverture\Models\Suggestion;
use amcsi\LyceeOverture\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SuggestionFactory extends Factory
{
    protected $model = Suggestion::class;

    public function definition()
    {
        return array_replace([
            'card_id' => Card::factory(),
            'creator_id' => User::factory(),
            'locale' => Locale::ENGLISH,
        ],
            app(FactoryCommon::class)->getSuggestableTranslatableProperties());
    }
}
