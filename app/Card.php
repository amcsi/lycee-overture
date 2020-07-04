<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method CardTranslation getTranslation($locale = null, $withFallback = null)
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

    public function set(): BelongsTo
    {
        return $this->belongsTo(Set::class);
    }

    /**
     * Gets the translation preferring the current locale and falling back to the auto-translated variant.
     */
    public function getBestTranslation(): CardTranslation
    {
        $locale = $this->locale();
        return $this->getTranslation($locale) ?: $this->getTranslation("$locale-auto");
    }

    public function suggestions()
    {
        return $this->hasMany(Suggestion::class);
    }
}
