<?php

use App\Controllers\ArticleController;
use App\Controllers\FrontController;
use App\Core\FileManager;
use App\Core\Helper;
use App\Models\Category;
use App\Models\Post;
use App\Models\Article;
use App\Views\ArticleView;
use App\Views\FrontView;

require '../vendor/autoload.php';  // composer autoload PSR-4

/* whoops — это платформа обработки ошибок для PHP.
* «Из коробки» он предоставляет красивый интерфейс ошибок,
* который помогает вам отлаживать ваши веб-проекты,
* но по сути это простая, но мощная многоуровневая система обработки ошибок.
*/
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();
// -----------------
$h = new Helper();// класс помощник
$config = require '../config/settings.php';


// создаём экземпляры классов
$article = new Article();
$article_view = new ArticleView();
$category = new Category();
$front_view = new FrontView();
$post = new Post();
$file_manager = new FileManager();
$article_controller = new ArticleController($article, $article_view, $category);
$front_controller = new FrontController($front_view, $category, $post);

// получаем URI
$uri = $_SERVER['REQUEST_URI'];

// проверяем совпадения маршрутов
switch ($uri) {
    case '/':
        $front_controller->index();
        break;
    case '/category/coding':
        $front_controller->showPostsInCategory('coding');
        break;
    case '/articles':
        $front_controller->showAllPosts();
        break;
    case '/calc':
        include_once(TEMPLATES_PATH . '/pages/calc.php');
        break;
    default:
        include_once(TEMPLATES_PATH . '/pages/404.php');
        break;
}
