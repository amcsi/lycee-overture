<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Http\Controllers;

use amcsi\LyceeOverture\Article;
use amcsi\LyceeOverture\Article\ArticleResource;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::orderByDesc('id')->paginate();

        return ArticleResource::collection($articles);
    }
}
