<?php

namespace App\Controllers;

use App\Views\ArticleView;
use App\Models\Article;

class ArticleController
{
    public Article $article;
    public ArticleView $articleView;

    public function __construct(Article $article, ArticleView $articleView)
    {
        $this->article = $article;
        $this->articleView = $articleView;

    }

    public function showArticlesList()
    {
        $articles = $this->article->all();
        $path = $_SERVER['DOCUMENT_ROOT'] . '/templates/articles/articles_list.php';
        $this->articleView->showArticlesList($path, $articles);
    }

}