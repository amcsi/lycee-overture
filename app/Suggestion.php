<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture;

use amcsi\LyceeOverture\Models\Traits\HasCreator;
use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    use HasCreator;

    protected $fillable = [
        'card_id',
        'locale',
        'basic_abilities',
        'pre_comments',
        'ability_cost',
        'ability_description',
        'comments',
    ];
}
