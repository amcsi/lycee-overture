<?php
declare(strict_types=1);

use amcsi\LyceeOverture\Card;
use amcsi\LyceeOverture\Card\Type;
use amcsi\LyceeOverture\CardTranslation;
use amcsi\LyceeOverture\I18n\JapaneseCharacterCounter;
use amcsi\LyceeOverture\I18n\Locale;
use amcsi\LyceeOverture\Suggestion;
use amcsi\LyceeOverture\User;
use Illuminate\Database\Eloquent\Factory;

$japaneseFaker = Faker\Factory::create('ja_JP');

/** @var Factory $factory */
$factory->define(
    Card::class,
    function () {
        return [
            'id' => 'LO-0001',
            'type' => Type::CHARACTER,
        ];
    });

$getSuggestableTranslatableProperties = function () use ($japaneseFaker) {
    return [
        'pre_comments' => '',
        'basic_abilities' => '[サイドステップ:[0]]',
        'ability_cost' => '[宣言] [0]',
        'ability_description' => $japaneseFaker->name,
        'comments' => $japaneseFaker->name,
    ];
};

$factory->define(
    CardTranslation::class,
    function () use ($japaneseFaker, $getSuggestableTranslatableProperties) {
        return array_replace([
            'card_id' => \factory(Card::class),
            'locale' => Locale::JAPANESE,
            'name' => $japaneseFaker->name,
            'ability_name' => $japaneseFaker->name,
        ],
            $getSuggestableTranslatableProperties());
    });

$factory->afterMaking(CardTranslation::class,
    function (CardTranslation $translation) {
        $translation->kanji_count = JapaneseCharacterCounter::countJapaneseCharactersForDbRow($translation->toArray());
    });

$factory->define(Suggestion::class,
    function () use ($getSuggestableTranslatableProperties) {
        return array_replace([
            'card_id' => \factory(Card::class),
            'creator_id' => \factory(User::class),
            'locale' => Locale::ENGLISH,
        ],
            $getSuggestableTranslatableProperties());
    }
);
