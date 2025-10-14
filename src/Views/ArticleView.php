<?php

namespace App\Views;

class ArticleView
{
    protected $html;

    public function showArticlesList(string $path, array $articles)
    {
        print $this->html = include_once($path);

    }
}