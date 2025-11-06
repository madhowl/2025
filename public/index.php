<?php

use App\Controllers\ArticleController;
use App\Views\ArticleView;
use App\Models\Article;
use MiladRahimi\PhpRouter\Router;
use MiladRahimi\PhpRouter\Exceptions\RouteNotFoundException;
use Laminas\Diactoros\Response\HtmlResponse;

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
$loader = new \Twig\Loader\FilesystemLoader(TEMPLATES_PATH);
$twig = new \Twig\Environment($loader, []);
$article = new Article();
$article_view = new ArticleView($twig);
$article_controller = new ArticleController($article, $article_view);



$router = Router::create();
$router->get('/', function () use ($twig) {
    $twig->render('/pges/index.php', []);
    //include_once('../templates/pages/index.php');
});
$router->get('/calc', function () {
    include_once('../templates/pages/calc.php');
});
$router->get('/articles', $article_controller, 'showArticlesList');
$router->get('/article/{id}', [$article_controller, 'showArticleById']);

$router->dispatch();
/*try {
    $router->dispatch();
} catch (RouteNotFoundException $e) {
    // It's 404!
    //$router->getPublisher()->publish(new HtmlResponse('Not found.', 404));
    include_once('../templates/pages/404.php');
} catch (Throwable $e) {
    // Log and report...
    $router->getPublisher()->publish(new HtmlResponse('Internal error.', 500));
}    */




