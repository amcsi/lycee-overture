<?php
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace amcsi\LyceeOverture {

    /**
     * Text (translations) for cards.
     *
     */
    class CardTranslation extends \Eloquent
    {
    }
}

namespace amcsi\LyceeOverture {

    /**
     * amcsi\LyceeOverture\Card
     *
     * @property-read \Illuminate\Database\Eloquent\Collection|\amcsi\LyceeOverture\CardTranslation[] $translations
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card listsTranslations($translationField)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card notTranslatedIn($locale = null)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card orWhereTranslation($key, $value, $locale = null)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card orWhereTranslationLike($key, $value, $locale = null)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card translated()
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card translatedIn($locale = null)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereTranslation($key, $value, $locale = null)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereTranslationLike($key, $value, $locale = null)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card withTranslation()
     */
    class Card extends \Eloquent
    {
    }
}

namespace amcsi\LyceeOverture {

    /**
     * amcsi\LyceeOverture\User
     *
     * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
     */
    class User extends \Eloquent
    {
    }
}

