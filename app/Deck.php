<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Deck newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Deck newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Deck query()
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Deck whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Deck whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Deck whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Deck whereNameJa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\Deck whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Deck extends Model
{
    protected $fillable = [
        'name_ja',
        'name_en',
        'cards',
    ];

    public function cards(): BelongsToMany
    {
        return $this->belongsToMany(Card::class)->using(CardDeck::class);
    }
}
