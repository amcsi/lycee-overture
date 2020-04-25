<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture;

use amcsi\LyceeOverture\Card\BrandMapper;
use amcsi\LyceeOverture\I18n\Locale;
use Illuminate\Database\Eloquent\Model;

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
