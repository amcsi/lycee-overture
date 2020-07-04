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
    // Columns that are translated in OneSky.
    public const NAME_COLUMNS = ['name', 'ability_name', 'character_type'];

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
