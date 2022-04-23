<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * amcsi\LyceeOverture\CardDeck
 *
 * @property string $card_id
 * @property int $deck_id
 * @property int $quantity
 * @property-read \amcsi\LyceeOverture\Card $card
 * @property-read \amcsi\LyceeOverture\Deck $deck
 * @method static \Illuminate\Database\Eloquent\Builder|CardDeck newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CardDeck newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CardDeck query()
 * @method static \Illuminate\Database\Eloquent\Builder|CardDeck whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardDeck whereDeckId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardDeck whereQuantity($value)
 * @mixin \Eloquent
 */
class CardDeck extends Pivot
{
    public $timestamps = false;

    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    public function deck()
    {
        return $this->belongsTo(Deck::class);
    }
}
