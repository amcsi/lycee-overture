<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Models;

use amcsi\LyceeOverture\Models\Card;
use amcsi\LyceeOverture\Models\Traits\HasCreator;
use amcsi\LyceeOverture\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
 * @property \Carbon\CarbonImmutable $updated_at
 * @property \Carbon\CarbonImmutable $created_at
 * @property-read \amcsi\LyceeOverture\Models\Card $card
 * @property-read \amcsi\LyceeOverture\Models\User|null $creator
 * @method static \Database\Factories\SuggestionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Suggestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Suggestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Suggestion query()
 * @method static \Illuminate\Database\Eloquent\Builder|Suggestion whereAbilityCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Suggestion whereAbilityDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Suggestion whereBasicAbilities($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Suggestion whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Suggestion whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Suggestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Suggestion whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Suggestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Suggestion whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Suggestion wherePreComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Suggestion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Suggestion extends Model
{
    use HasCreator, HasFactory;

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
