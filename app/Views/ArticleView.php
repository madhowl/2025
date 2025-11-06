<?php

namespace App\Views;

class ArticleView
{
    private  $twig;

    public function __construct( $twig)
    {
        $this->twig = $twig ;
    }
    protected $html;

    public function showArticlesList(string $path, array $articles)
    {
        print $this->html = include_once($path);

    }
    public function showSingleArticle(string $path, array $article)
    {
        extract($article);
        //print $this->html = include_once($path);
        echo $this->twig->render('/articles/article.php', [$article]);

    }
}