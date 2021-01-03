<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CardDeck extends Pivot
{
    public $timestamps = false;
}
