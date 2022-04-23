<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture;

use amcsi\LyceeOverture\Card\BrandMapper;
use amcsi\LyceeOverture\I18n\Locale;
use Illuminate\Database\Eloquent\Model;

/**
 * amcsi\LyceeOverture\Set
 *
 * @property int $id
 * @property string $name_ja
 * @property string $version
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read mixed $brand
 * @method static \Illuminate\Database\Eloquent\Builder|Set newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Set newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Set query()
 * @method static \Illuminate\Database\Eloquent\Builder|Set whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Set whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Set whereNameJa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Set whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Set whereVersion($value)
 * @mixin \Eloquent
 */
class Set extends Model
{
    protected $table = 'sets';

    public function getFullName(string $locale): string
    {
        $configKey = "lycee.sets.{$this->name_ja}";
        $name = $locale === Locale::JAPANESE || !($translated = config($configKey)) ? $this->name_ja : $translated;

        return sprintf('%s %s', $name, $this->version);
    }

    public function getBrandAttribute()
    {
        return BrandMapper::getBrand($this->name_ja);
    }
}
