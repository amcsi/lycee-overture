<?php
declare(strict_types=1);

use amcsi\LyceeOverture\Card;
use amcsi\LyceeOverture\Card\Type;
use amcsi\LyceeOverture\CardTranslation;
use amcsi\LyceeOverture\I18n\Locale;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(
    Card::class,
    function () {
        return [
            'id' => 'LO-0001',
            'type' => Type::CHARACTER,
        ];
    });

$factory->define(
    CardTranslation::class,
    function () {
        return [
            'card_id' => 'LO-0001',
            'locale' => Locale::JAPANESE,
        ];
    });
