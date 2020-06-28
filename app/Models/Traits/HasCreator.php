<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Models\Traits;

use Illuminate\Database\Eloquent\Model;

/**
 * Automatically keeps track of the owner of a resource.
 *
 * @mixin Model
 */
trait HasCreator
{
    protected static function bootHasCreator(): void
    {
        static::creating(function (Model $model) {
            if (!$model->creator_id) {
                $model->creator_id = \Auth::id();
            }
        });
    }
}
