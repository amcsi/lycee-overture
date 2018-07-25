<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture;

use Illuminate\Database\Eloquent\Model;

class CardImage extends Model
{
    protected $dates = ['last_uploaded'];

    public function getMd5(): string
    {
        return $this->getAttribute('md5');
    }

    public function getLastUploaded(): ?\DateTimeInterface
    {
        return $this->getAttribute('last_uploaded');
    }
}
