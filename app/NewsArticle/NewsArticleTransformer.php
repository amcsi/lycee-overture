<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\NewsArticle;

use amcsi\LyceeOverture\Api\GenericTransformers\DateTimeTransformer;
use amcsi\LyceeOverture\NewsArticle;
use League\Fractal\TransformerAbstract;

class NewsArticleTransformer extends TransformerAbstract
{
    private $dateTimeTransformer;

    public function __construct(DateTimeTransformer $dateTimeTransformer)
    {
        $this->dateTimeTransformer = $dateTimeTransformer;
    }

    public function transform(NewsArticle $newsArticle): array
    {
        return [
            'title' => $newsArticle->title,
            'html' => $newsArticle->html,
            'created_at' => $this->dateTimeTransformer->transform($newsArticle->created_at),
        ];
    }
}
