<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @method getTranslation($locale = null, $withFallback = null): CardTranslation
 */
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

    protected $dateFormat = 'Y-m-d H:i:s.u';

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
