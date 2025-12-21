<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Models;

use amcsi\LyceeOverture\Models\Card;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Text (translations) for cards.
 *
 * @property int $id
 * @property string $card_id
 * @property string $name
 * @property string $basic_abilities
 * @property string $ability_name
 * @property string $pre_comments
 * @property string $ability_cost
 * @property string $ability_description
 * @property string $comments
 * @property string $locale
 * @property int $kanji_count
 * @property \Carbon\CarbonImmutable $updated_at
 * @property \Carbon\CarbonImmutable $created_at
 * @property string $character_type
 * @property-read \amcsi\LyceeOverture\Models\Card $card
 * @method static \Database\Factories\CardTranslationFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|CardTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CardTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CardTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|CardTranslation whereAbilityCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardTranslation whereAbilityDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardTranslation whereAbilityName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardTranslation whereBasicAbilities($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardTranslation whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardTranslation whereCharacterType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardTranslation whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardTranslation whereKanjiCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardTranslation wherePreComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CardTranslation extends Model
{
    use HasFactory;

    // Columns that are translated in OneSky.
    public const NAME_COLUMNS = ['name', 'ability_name', 'character_type'];
    // Columns that are translated locally.
    public const TEXT_COLUMNS = ['basic_abilities', 'ability_description', 'ability_cost', 'pre_comments', 'comments'];

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
