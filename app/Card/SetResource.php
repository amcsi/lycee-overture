<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Card;

use amcsi\LyceeOverture\Set;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Set $resource
 */
class SetResource extends JsonResource
{
    public function toArray($request): array
    {
        $set = $this->resource;
        return [
            'id' => $set->id,
            'full_name' => $set->getFullName(\App::getLocale()),
            'brand' => BrandMapper::getBrand($set->name_ja),
        ];
    }
}
