<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Card extends Model
{
    use Translatable;

    public $incrementing = false;

    public $translatedAttributes = [
        'name',
        'ability_name',
        'ability_cost',
        'ability_description',
    ];

    protected $with = ['translations'];

    public function getId(): string
    {
        return $this->getAttribute('id');
    }

    public function getType(): int
    {
        return $this->getAttribute('type');
    }

    public function set(): HasOne
    {
        return $this->hasOne(Set::class);
    }
}
