<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * amcsi\LyceeOverture\Deck
 *
 * @property int $id
 * @property string $name_ja
 * @property string $name_en
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\amcsi\LyceeOverture\Card[] $cards
 * @property-read int|null $cards_count
 * @method static \Database\Factories\DeckFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Deck newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Deck newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Deck query()
 * @method static \Illuminate\Database\Eloquent\Builder|Deck whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deck whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deck whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deck whereNameJa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deck whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Deck extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ja',
        'name_en',
        'cards',
    ];

    public function cards(): BelongsToMany
    {
        return $this->belongsToMany(Card::class)->withPivot('quantity')->using(CardDeck::class);
    }
}
