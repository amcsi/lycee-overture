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
 * @property \Carbon\CarbonImmutable $updated_at
 * @property \Carbon\CarbonImmutable $created_at
 * @property \Carbon\CarbonImmutable|null $last_uploaded
 * @method static \Illuminate\Database\Eloquent\Builder|CardImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CardImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CardImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|CardImage whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardImage whereLastUploaded($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardImage whereMd5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardImage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CardImage extends Model
{
    protected $casts = ['last_uploaded' => 'datetime'];

    public function getMd5(): string
    {
        return $this->getAttribute('md5');
    }

    public function getLastUploaded(): ?\DateTimeInterface
    {
        return $this->getAttribute('last_uploaded');
    }
}
