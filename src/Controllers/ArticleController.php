<?php

namespace Controllers;

use Views\ArticleView;

class ArticleController
{
    public $article;
    public ArticleView $articleView;

    public function __construct($article, $articleView)
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