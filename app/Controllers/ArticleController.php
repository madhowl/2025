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
        $path = TEMPLATES_PATH.'/articles/articles_list.php';
        $this->articleView->showArticlesList($path, $articles);
    }
    public function showArticleById($id)
    {
        $article = $this->article->getArticleById($id);
        //var_dump($article);
        $path = TEMPLATES_PATH.'/articles/article.php';
        $this->articleView->showSingleArticle($path, $article);
    }

}