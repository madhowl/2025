<?php

namespace App\Controllers;

use AllowDynamicProperties;
use App\Core\FileManager;
use App\Models\Category;
use App\Models\Post;
use App\Traits\Helper;
use App\Views\FrontView;

#[AllowDynamicProperties] class FrontController
{
    use Helper;
    private FrontView $view;
    private Category $categoryModel;
    private Post $postModel;
    private FileManager $fm;

    public function __construct(FrontView $view, Category $categoryModel, Post $postModel)
    {
        $this->view = $view;
        $this->categoryModel = $categoryModel;
        $this->postModel = $postModel;
        $this->fileManager = new FileManager();

    }

    public function index(): void
    {
        $this->view->render('layout.php');
    }

    public function page($page): void
    {

    }

    public function page404()
    {

    }

    public function showPostsInCategory($categorySlug)
    {
        $categories = $this->categoryModel->getCategories();
        $posts= $this->postModel->getPostsInCategory($categorySlug);
        $this->view->render('posts.php',['posts'=>$posts,'categories'=>$categories]);

    }

    public function showPost($postSlug)
    {

    }

    public function showAllPosts()
    {
        $categories = $this->categoryModel->getCategories();
        $posts= $this->postModel->getAllPosts();
        $this->view->render('posts.php',['posts'=>$posts,'categories'=>$categories]);

    }

}