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
        $transKey = "sets.{$this->name_ja}";
        $name = $locale === Locale::JAPANESE || ($translated = trans($transKey)) === $transKey ?
            $this->name_ja :
            $translated;

        return sprintf('%s %s', $name, $this->version);
    }
}
