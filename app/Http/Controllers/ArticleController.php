<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Http\Controllers;

use amcsi\LyceeOverture\Article;
use amcsi\LyceeOverture\Article\ArticleTransformer;

class ArticleController extends Controller
{
    public function index(ArticleTransformer $articleTransformer)
    {
        $articles = Article::orderByDesc('id')->paginate();

        return $this->response->paginator($articles, $articleTransformer);
    }
}
