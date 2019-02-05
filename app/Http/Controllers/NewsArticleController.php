<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Http\Controllers;

use amcsi\LyceeOverture\NewsArticle;
use amcsi\LyceeOverture\NewsArticle\NewsArticleTransformer;

class NewsArticleController extends Controller
{
    public function index(NewsArticleTransformer $newsArticleTransformer)
    {
        $newsArticles = NewsArticle::orderByDesc('id')->paginate();

        return $this->response->paginator($newsArticles, $newsArticleTransformer);
    }
}
