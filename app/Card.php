<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * amcsi\LyceeOverture\Card
 *
 * @property string $id
 * @property int|null $set_id
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
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \amcsi\LyceeOverture\Set|null $set
 * @property-read \Illuminate\Database\Eloquent\Collection|\amcsi\LyceeOverture\Suggestion[] $suggestions
 * @property-read int|null $suggestions_count
 * @property-read \amcsi\LyceeOverture\CardTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\amcsi\LyceeOverture\CardTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card listsTranslations($translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card notTranslatedIn($locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card orWhereTranslation($translationField, $value, $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card orWhereTranslationLike($translationField, $value, $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card orderByTranslation($translationField, $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card query()
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
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereSetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereSnow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereSp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereSpace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereSun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereTranslation($translationField, $value, $locale = null, $method = 'whereHas', $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereTranslationLike($translationField, $value, $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereType($value)0
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card whereVariants($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Card withTranslation()
 * @mixin \Eloquent
 */
class Card extends Model
{
    use HasFactory, Translatable;

    public $incrementing = false;

    public $translatedAttributes = [
        'name',
        'ability_name',
        'ability_cost',
        'ability_description',
    ];

    protected $with = ['translations'];

    protected $dateFormat = 'Y-m-d H:i:s.u';

    public function getId(): string
    {
        return $this->getAttribute('id');
    }

    public function getType(): int
    {
        return $this->getAttribute('type');
    }

    public function set(): BelongsTo
    {
        return $this->belongsTo(Set::class);
    }

    /**
     * Gets the translation preferring the current locale and falling back to the auto-translated variant.
     */
    public function getBestTranslation(): CardTranslation
    {
        $locale = $this->locale();
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->getTranslation($locale) ?: $this->getTranslation("$locale-auto");
    }

    public function suggestions()
    {
        return $this->hasMany(Suggestion::class);
    }
}
