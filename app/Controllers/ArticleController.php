<?php

namespace App\Controllers;

use App\Models\Category;
use App\Traits\Helper;
use App\Views\ArticleView;
use App\Models\Article;

class ArticleController
{
    use Helper;
    private Article $article;
    private ArticleView $articleView;
    private Category $category;

    public function __construct(Article $article, ArticleView $articleView,Category $category)
    {
        $this->article = $article;
        $this->articleView = $articleView;
        $this->category = $category;

    }

    public function showArticlesList()
    {
        $articles = $this->article->getAllArticles();
        $articles = $this->article->all();
        $categories = $this->category->getCategories();
        //$this->dd($categories);
        $path = TEMPLATES_PATH.'/articles/articles_list.php';
        $this->articleView->showArticlesList($path, $articles, $categories);
    }



}