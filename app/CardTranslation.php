<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture;

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
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property string $character_type
 * @property-read \amcsi\LyceeOverture\Card $card
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardTranslation whereAbilityCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardTranslation whereAbilityDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardTranslation whereAbilityName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardTranslation whereBasicAbilities($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardTranslation whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardTranslation whereCharacterType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardTranslation whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardTranslation whereKanjiCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardTranslation wherePreComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CardTranslation extends Model
{
    use HasFactory;

    // Columns that are translated in OneSky.
    public const NAME_COLUMNS = ['name', 'ability_name', 'character_type'];
    // Columns that are translated locally.
    public const TEXT_COLUMNS = ['ability_description', 'ability_cost', 'pre_comments', 'comments'];

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
