<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Article;

use amcsi\LyceeOverture\Api\GenericTransformers\DateTimeTransformer;
use amcsi\LyceeOverture\Article;
use League\Fractal\TransformerAbstract;

class ArticleTransformer extends TransformerAbstract
{
    private $dateTimeTransformer;

    public function __construct(DateTimeTransformer $dateTimeTransformer)
    {
        $this->dateTimeTransformer = $dateTimeTransformer;
    }

    public function transform(Article $article): array
    {
        return [
            'title' => $article->title,
            'html' => $article->html,
            'created_at' => $this->dateTimeTransformer->transform($article->created_at),
        ];
    }
}
