<?php
declare(strict_types=1);

namespace Database\Factories;

use Faker\Factory;

class FactoryCommon
{
    private \Faker\Generator $japaneseFaker;

    public function __construct()
    {
        $this->japaneseFaker = Factory::create('ja_JP');
    }

    public function getSuggestableTranslatableProperties(): array
    {
        return [
            'pre_comments' => '',
            'basic_abilities' => '[サイドステップ:[0]]',
            'ability_cost' => '[宣言] [0]',
            'ability_description' => $this->japaneseFaker->name,
            'comments' => $this->japaneseFaker->name,
        ];
    }
}
