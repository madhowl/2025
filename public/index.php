<?php

use App\Controllers\ArticleController;
use App\Models\Category;
use App\Views\ArticleView;
use App\Models\Article;

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
$h =new \App\Core\Helper();// класс помошник
$config = require '../config/settings.php';


// создаём экземпляры классов
$article = new Article();
$article_view = new ArticleView();
$category = new Category();
$article_controller = new ArticleController($article, $article_view, $category);

// получаем URI
$uri = $_SERVER['REQUEST_URI'];

// проверяем совпадения маршрутов
switch ($uri) {
    case '/':
        include_once(TEMPLATES_PATH.'/pages/index.php');
        break;
    case '/articles':
        $article_controller->showArticlesList();
        break;
    case '/calc':
        include_once(TEMPLATES_PATH.'/pages/calc.php');
        break;
    default:
        include_once(TEMPLATES_PATH.'/pages/404.php');
        break;
}
