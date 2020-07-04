<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\CardTranslation;
use amcsi\LyceeOverture\I18n\Locale;
use Illuminate\Database\Query\JoinClause;

/**
 * Keeps properties translated in OneSky in sync between auto and manual card translations.
 */
class NameSyncer
{
    public static function syncNames(): void
    {
        $toUpdateKeys = CardTranslation::NAME_COLUMNS;

        CardTranslation::whereIn('card_translations.locale', Locale::TRANSLATION_LOCALES)
            ->join('card_translations as autoTranslations',
                function (JoinClause $joinClause) {
                    $joinClause->on('card_translations.card_id', '=', 'autoTranslations.card_id')
                        ->where('autoTranslations.locale', '=', \DB::raw("concat(card_translations.locale, '-auto')"));
                })
            ->update(array_combine(
                array_map(fn($key) => "card_translations.$key", $toUpdateKeys),
                array_map(fn($key) => \DB::raw("autoTranslations.$key"), $toUpdateKeys)
            ));
    }
}
