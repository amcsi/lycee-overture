<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * amcsi\LyceeOverture\CardDeck
 *
 * @property string $card_id
 * @property int $deck_id
 * @property int $quantity
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
}
