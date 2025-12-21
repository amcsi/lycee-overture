<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\ManualTranslation;

use amcsi\LyceeOverture\Models\Suggestion;
use amcsi\LyceeOverture\User\UserBareResource;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Suggestion $resource
 */
class SuggestionResource extends JsonResource
{
    public function toArray($request)
    {
        $item = $this->resource;
        $ret = $item->attributesToArray();
        $ret['creator'] = new UserBareResource($this->whenLoaded('creator'));
        return $ret;
    }
}
