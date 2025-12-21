<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Models;

use amcsi\LyceeOverture\Models\CardTranslation;
use amcsi\LyceeOverture\Models\Set;
use amcsi\LyceeOverture\Models\Suggestion;
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
 * @property \Carbon\CarbonImmutable $created_at
 * @property \Carbon\CarbonImmutable $updated_at
 * @property-read \amcsi\LyceeOverture\Models\Set|null $set
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \amcsi\LyceeOverture\Models\Suggestion> $suggestions
 * @property-read int|null $suggestions_count
 * @property-read \amcsi\LyceeOverture\Models\CardTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \amcsi\LyceeOverture\Models\CardTranslation> $translations
 * @property-read int|null $translations_count
 * @method static \Database\Factories\CardFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Card listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Card newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Card newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Card notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Card orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Card orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Card orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Card query()
 * @method static \Illuminate\Database\Eloquent\Builder|Card translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Card translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereAbilityType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereAp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereCostFlower($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereCostMoon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereCostSnow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereCostSpace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereCostStar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereCostSun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereDmg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereDp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereEx($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereFlower($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereMoon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereRarity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereSetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereSnow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereSp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereSpace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereSun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereVariants($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card withTranslation()
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

    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $keyType = 'string';

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
        $ret = $this->getTranslation($locale) ?:
            $this->getTranslation("$locale-auto");
        if (!$ret) {
            $ret = $this->getTranslation('ja');
            $ret->pre_comments = trim("(Automatic translation failure)\n{$ret->pre_comments}");
        }

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $ret;
    }

    public function suggestions()
    {
        return $this->hasMany(Suggestion::class);
    }
}
