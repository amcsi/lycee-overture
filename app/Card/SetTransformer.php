<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Card;

use amcsi\LyceeOverture\Set;
use League\Fractal\TransformerAbstract;

class SetTransformer extends TransformerAbstract
{
    public function transform(Set $set): array
    {
        return [
            'id' => $set->id,
            'full_name' => $set->getFullName(\App::getLocale()),
            'brand' => BrandMapper::getBrand($set->name_ja),
        ];
    }
}
