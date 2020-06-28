<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\ManualTranslation;

use amcsi\LyceeOverture\Suggestion;
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
        $ret = $item->toArray();
        $ret['creator'] = new UserBareResource($this->whenLoaded('creator'));
        return $ret;
    }
}
