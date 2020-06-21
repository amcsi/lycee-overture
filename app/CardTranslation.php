<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Text (translations) for cards.
 */
class CardTranslation extends Model
{
    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
