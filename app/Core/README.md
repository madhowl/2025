# 🚦 Простой Роутер для PHP

Минималистичный, но мощный роутер для PHP-приложений. Поддерживает маршруты с параметрами, разные HTTP-методы и кастомные страницы ошибок.

---

## 📦 Установка

1. Скачайте файл [`Router.php`](Router.php) в папку вашего проекта.
2. Подключите его в основном файле (обычно `index.php`):

```php
require_once 'Router.php';
```

---

## 🧭 Базовое использование

### 1. Создайте роутер:scream:

```php
$router = new Router();
```

### 2. Зарегистрируйте маршруты

```php
// GET-запросы
$router->get('/', function() {
    echo "Главная страница";
});

$router->get('/user/{id}', function($id) {
    echo "Просмотр пользователя: " . htmlspecialchars($id);
});

// POST-запросы
$router->post('/submit', function() {
    echo "Данные получены!";
});

// PUT, DELETE
$router->put('/user/{id}', 'UserController@update');
$router->delete('/user/{id}', 'UserController@delete');

// Любой метод
$router->any('/catch-all', function() {
    echo "Любой запрос сюда";
});
```

### 3. Запустите маршрутизацию

```php
$router->run();
```

---

## 🏗️ Поддержка контроллеров

Вы можете использовать контроллеры двумя способами:

### Способ 1: Строка вида `'Контроллер@метод'`

```php
$router->get('/home', 'HomeController@index');
$router->get('/user/{id}', 'UserController@show');
```

> ⚠️ Класс должен быть подключён (через `require` или автозагрузку).

Пример контроллера (`UserController.php`):

```php
class UserController
{
    public function show($id)
    {
        echo "Пользователь ID: " . htmlspecialchars($id);
    }
}
```

### Способ 2: Передача экземпляра

```php
$userController = new UserController();
$router->get('/user/{id}', [$userController, 'show']);
```

---

## 🎨 Кастомная страница 404

По умолчанию выводится: `404 - Страница не найдена`.

Чтобы изменить — используйте `setNotFoundHandler()`:

### Через анонимную функцию

```php
$router->setNotFoundHandler(function() {
    echo '<h1>Ошибка 404</h1>';
    echo '<p>Страница не существует. <a href="/">Вернуться на главную</a></p>';
});
```

### Через контроллер

```php
$router->setNotFoundHandler('ErrorController@notFound');
```

Пример (`ErrorController.php`):

```php
class ErrorController
{
    public function notFound()
    {
        http_response_code(404); // Уже установлено, но можно переопределить
        include 'views/404.html';
    }
}
```

> 💡 HTTP-статус **автоматически устанавливается в 404** до вызова обработчика.

---

## 📌 Особенности

- **Параметры в URL**: `{name}` — захватывает любой текст до следующего `/`.
  - Пример: `/post/{slug}` → совпадает с `/post/hello-world`, но не с `/post/hello/world`.
- **Порядок маршрутов важен**: первый совпавший маршрут выполняется.
- **Нормализация путей**: `/user/` и `/user` считаются одинаковыми.
- **Безопасность**: всегда используйте `htmlspecialchars()` при выводе данных из URL!

---

## 🛠️ Требования

- PHP 7.4+ (рекомендуется PHP 8.0+ для `str_contains`)
- Для PHP < 8.0 замените `str_contains($a, $b)` на `strpos($a, $b) !== false`

---

## 📚 Пример полного `index.php`

```php
<?php
require_once 'Router.php';
require_once 'HomeController.php';
require_once 'ErrorController.php';

$router = new Router();

$router->get('/', function() {
    echo "Добро пожаловать!";
});

$router->get('/user/{id}', 'HomeController@showUser');
$router->post('/submit', function() {
    echo "Форма отправлена!";
});

$router->setNotFoundHandler('ErrorController@notFound');

$router->run();
```

---

## 📜 Лицензия

MIT — используйте свободно в любых проектах.
