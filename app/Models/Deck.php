<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Models;

use amcsi\LyceeOverture\Models\Card;
use amcsi\LyceeOverture\Models\CardDeck;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * amcsi\LyceeOverture\Deck
 *
 * @property int $id
 * @property string $name_ja
 * @property string $name_en
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \amcsi\LyceeOverture\Models\Card> $cards
 * @property-read int|null $cards_count
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
