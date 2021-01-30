<?php
declare(strict_types=1);

namespace Database\Factories;

use amcsi\LyceeOverture\Card;
use amcsi\LyceeOverture\Card\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

class CardFactory extends Factory
{
    private static $increment = 1;
    protected $model = Card::class;

    public function definition()
    {
        $idNumber = self::$increment++;
        return [
            'id' => sprintf('LO-%04d', $idNumber),
            'type' => Type::CHARACTER,
        ];
    }
}
