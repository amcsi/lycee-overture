<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture;

use Illuminate\Database\Eloquent\Model;

/**
 * amcsi\LyceeOverture\CardImage
 *
 * @property int $id
 * @property string $card_id
 * @property string $md5
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $last_uploaded
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardImage whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardImage whereLastUploaded($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardImage whereMd5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\amcsi\LyceeOverture\CardImage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
