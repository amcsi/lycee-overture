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
     * amcsi\LyceeOverture\CardSet
     *
     * @property int $id
     * @property string $name_ja
     * @property string $name_en
     * @property string $cards
     * @property int $deck
     * @property \Carbon\Carbon|null $created_at
     * @property \Carbon\Carbon|null $updated_at
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardSet whereCards($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardSet whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardSet whereDeck($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardSet whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardSet whereNameEn($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardSet whereNameJa($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardSet whereUpdatedAt($value)
     */
    class CardSet extends \Eloquent
    {
    }
}

namespace amcsi\LyceeOverture {

    /**
     * amcsi\LyceeOverture\CardImage
     *
     * @property int $id
     * @property string $card_id
     * @property string $md5
     * @property \Carbon\Carbon $updated_at
     * @property \Carbon\Carbon $created_at
     * @property \Carbon\Carbon|null $last_uploaded
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardImage whereCardId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardImage whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardImage whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardImage whereLastUploaded($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardImage whereMd5($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardImage whereUpdatedAt($value)
     */
    class CardImage extends \Eloquent
    {
    }
}

namespace amcsi\LyceeOverture {

    /**
     * Text (translations) for cards.
     *
     * @property int $id
     * @property string $card_id
     * @property string $name
     * @property string $basic_abilities
     * @property string $ability_name
     * @property string $ability_cost
     * @property string $ability_description
     * @property string $comments
     * @property string $locale
     * @property int $kanji_count
     * @property \Carbon\Carbon $updated_at
     * @property \Carbon\Carbon $created_at
     * @property string $character_type
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardTranslation
     *     whereAbilityCost($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardTranslation
     *     whereAbilityDescription($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardTranslation
     *     whereAbilityName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardTranslation
     *     whereBasicAbilities($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardTranslation whereCardId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardTranslation
     *     whereCharacterType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardTranslation whereComments($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardTranslation whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardTranslation whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardTranslation
     *     whereKanjiCount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardTranslation whereLocale($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardTranslation whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardTranslation whereUpdatedAt($value)
     */
    class CardTranslation extends \Eloquent
    {
    }
}

namespace amcsi\LyceeOverture {

    /**
     * amcsi\LyceeOverture\Card
     *
     * @property string $id
     * @property string $variants
     * @property string $rarity
     * @property int $type
     * @property int $ex
     * @property int $snow
     * @property int $moon
     * @property int $flower
     * @property int $space
     * @property int $sun
     * @property int $cost_star
     * @property int $cost_snow
     * @property int $cost_moon
     * @property int $cost_flower
     * @property int $cost_space
     * @property int $cost_sun
     * @property int $ap
     * @property int $dp
     * @property int $sp
     * @property int $dmg
     * @property int $ability_type
     * @property \Carbon\Carbon $created_at
     * @property \Carbon\Carbon $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection|\amcsi\LyceeOverture\CardTranslation[] $translations
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card
     *     listsTranslations($translationField)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card notTranslatedIn($locale = null)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card orWhereTranslation($key, $value,
     *     $locale = null)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card orWhereTranslationLike($key,
     *     $value, $locale = null)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card orderByTranslation($key,
     *     $sortmethod = 'asc')
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card translated()
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card translatedIn($locale = null)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereAbilityType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereAp($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereCostFlower($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereCostMoon($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereCostSnow($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereCostSpace($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereCostStar($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereCostSun($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereDmg($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereDp($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereEx($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereFlower($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereMoon($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereRarity($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereSnow($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereSp($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereSpace($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereSun($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereTranslation($key, $value,
     *     $locale = null)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereTranslationLike($key,
     *     $value, $locale = null)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereVariants($value)
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
     * @property int $id
     * @property string $name
     * @property string $email
     * @property string $password
     * @property string|null $remember_token
     * @property \Carbon\Carbon|null $created_at
     * @property \Carbon\Carbon|null $updated_at
     * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[]
     *     $notifications
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\User whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\User whereEmail($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\User whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\User whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\User wherePassword($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\User whereRememberToken($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\User whereUpdatedAt($value)
     */
    class User extends \Eloquent
    {
    }
}

