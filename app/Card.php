<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use Translatable;

    public $incrementing = false;

    public $translatedAttributes = [
        'name',
        'ability_name',
        'ability_description',
    ];

    protected $with = ['translations'];

    public function getType(): int
    {
        return $this->getAttribute('type');
    }
}
