<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture;

use amcsi\LyceeOverture\Models\Traits\HasCreator;
use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    use HasCreator;

    public const SUGGESTABLE_PROPERTIES = [
        'basic_abilities',
        'pre_comments',
        'ability_cost',
        'ability_description',
        'comments',
    ];

    protected $fillable = [
        'card_id',
        'locale',
        'basic_abilities',
        'pre_comments',
        'ability_cost',
        'ability_description',
        'comments',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}
