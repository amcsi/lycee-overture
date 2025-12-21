<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Article;

use amcsi\LyceeOverture\Models\Article;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Article $resource
 */
class ArticleResource extends JsonResource
{
    public function toArray($request): array
    {
        $article = $this->resource;
        return [
            'title' => $article->title,
            'html' => $article->html,
            'created_at' => $article->created_at,
        ];
    }
}
