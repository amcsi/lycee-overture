<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture;

use Illuminate\Database\Eloquent\Model;

class Deck extends Model
{
    protected $fillable = [
        'name_ja',
        'name_en',
        'cards',
    ];
}
