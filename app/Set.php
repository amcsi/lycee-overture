<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture;

use amcsi\LyceeOverture\I18n\Locale;
use Illuminate\Database\Eloquent\Model;

class Set extends Model
{
    protected $table = 'sets';

    public function getFullName(string $locale): string
    {
        $name = $locale === Locale::JAPANESE ? $this->name_ja : $this->name_en;

        return sprintf('%s %s', $name, $this->version);
    }
}
