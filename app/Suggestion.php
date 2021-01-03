<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture;

use amcsi\LyceeOverture\Models\Traits\HasCreator;
use Illuminate\Database\Eloquent\Model;

/**
 * amcsi\LyceeOverture\Suggestion
 *
 * @property int $id
 * @property string $card_id
 * @property int|null $creator_id
 * @property string $locale
 * @property string $basic_abilities
 * @property string $pre_comments
 * @property string $ability_cost
 * @property string $ability_description
 * @property string $comments
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read \amcsi\LyceeOverture\Card $card
 * @property-read \amcsi\LyceeOverture\User|null $creator
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Suggestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Suggestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Suggestion query()
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Suggestion whereAbilityCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Suggestion whereAbilityDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Suggestion whereBasicAbilities($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Suggestion whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Suggestion whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Suggestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Suggestion whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Suggestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Suggestion whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Suggestion wherePreComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Suggestion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Suggestion extends Model
{
    use HasCreator;

    public const SUGGESTABLE_PROPERTIES = [
        'basic_abilities',
        'pre_comments',
        'ability_cost',
        'ability_description',
        'comments',
    ];

    protected $fillable = [
        'card_id',
        'locale',
        'basic_abilities',
        'pre_comments',
        'ability_cost',
        'ability_description',
        'comments',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}
