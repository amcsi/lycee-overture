<?php
declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DeckFactory extends Factory
{
    public function definition()
    {
        return [
            'name_en' => $this->faker->name,
            'name_ja' => \Faker\Factory::create('ja_JP')->name,
        ];
    }
}
