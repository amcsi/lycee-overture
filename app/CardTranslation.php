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
    // Columns that are translated locally.
    public const TEXT_COLUMNS = ['ability_description', 'ability_cost', 'pre_comments', 'comments'];

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
