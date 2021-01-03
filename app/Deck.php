<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
