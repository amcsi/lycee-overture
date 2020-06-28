<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\User;

use amcsi\LyceeOverture\User;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 * @mixin JsonResource
 */
class UserBareResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
