<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    public function getType(): int
    {
        return $this->getAttribute('type');
    }
}
