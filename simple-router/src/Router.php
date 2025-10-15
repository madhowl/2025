<?php

namespace MadHowl\SimpleRouter;



use Exception;

/**
 * Простой, но гибкий роутер для PHP-приложений.
 *
 * Возможности:
 * - Регистрация маршрутов для GET, POST, PUT, DELETE и любого метода (*).
 * - Поддержка параметров в URL: /user/{id}, /post/{slug}/{lang} и т.д.
 * - Обработчики маршрутов могут быть:
 *     • Анонимными функциями (замыканиями),
 *     • Массивами вида [$object, 'methodName'],
 *     • Строками вида 'ControllerClassName@methodName'.
 * - Кастомная страница ошибки 404 с полной свободой оформления.
 *
 * Пример использования:
 *   $router = new Router();
 *   $router->get('/user/{id}', 'UserController@show');
 *   $router->setNotFoundHandler(function() { echo "Страница не найдена"; });
 *   $router->run();
 */
class Router
{
    /**
     * Хранит все зарегистрированные маршруты.
     * Каждый элемент — ассоциативный массив с ключами:
     *   - 'method': HTTP-метод ('GET', 'POST', '*', и т.д.)
     *   - 'path': нормализованный путь (без слэшей по краям)
     *   - 'handler': обработчик маршрута
     *
     * @var array
     */
    private array $routes = [];

    /**
     * HTTP-метод текущего запроса (например: 'GET', 'POST').
     *
     * @var string
     */
    private string $method;

    /**
     * URI текущего запроса, нормализованный: без начального и конечного слэша.
     * Например: для '/api/user/123/' → 'api/user/123'
     *
     * @var string
     */
    private string $uri;

    /**
     * Кастомный обработчик для ошибки 404.
     * Может быть:
     *   - callable (анонимная функция, [объект, 'метод']),
     *   - строка вида 'Controller@method',
     *   - null (тогда используется стандартное сообщение).
     *
     * @var callable|string|null
     */
    private $notFoundHandler = null;

    /**
     * Конструктор.
     * Инициализирует данные текущего HTTP-запроса:
     * - извлекает метод запроса,
     * - извлекает путь из REQUEST_URI,
     * - нормализует путь (удаляет слэши по краям).
     */
    public function __construct()
    {
        // Получаем HTTP-метод. Если по какой-то причине не задан — используем 'GET' по умолчанию.
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        // Получаем полный URI запроса (включая query string), но нам нужен только путь.
        $requestUri = $_SERVER['REQUEST_URI'] ?? '/';

        // Извлекаем только путь (без ?query=string и #fragment)
        $path = parse_url($requestUri, PHP_URL_PATH);

        // Нормализуем: убираем начальный и конечный слэши, чтобы '/user/' и 'user' были одинаковыми.
        $this->uri = trim($path, '/');
    }

    /**
     * Регистрирует маршрут для HTTP-метода GET.
     *
     * @param string $path Путь маршрута (например: '/user/{id}')
     * @param mixed $handler Обработчик маршрута (функция, контроллер и т.д.)
     * @return self Возвращает $this для цепочки вызовов (fluent interface)
     */
    public function get(string $path, $handler): self
    {
        return $this->add('GET', $path, $handler);
    }

    /**
     * Регистрирует маршрут для HTTP-метода POST.
     */
    public function post(string $path, $handler): self
    {
        return $this->add('POST', $path, $handler);
    }

    /**
     * Регистрирует маршрут для HTTP-метода PUT.
     */
    public function put(string $path, $handler): self
    {
        return $this->add('PUT', $path, $handler);
    }

    /**
     * Регистрирует маршрут для HTTP-метода DELETE.
     */
    public function delete(string $path, $handler): self
    {
        return $this->add('DELETE', $path, $handler);
    }

    /**
     * Регистрирует маршрут для любого HTTP-метода.
     */
    public function any(string $path, $handler): self
    {
        return $this->add('*', $path, $handler);
    }

    /**
     * Устанавливает кастомный обработчик для ошибки 404 "Страница не найдена".
     * Обработчик может быть:
     *   - анонимной функцией: function() { echo "..."; }
     *   - строкой: 'ErrorController@notFound'
     *   - массивом: [$errorController, 'show404']
     *
     * @param callable|string $handler Кастомный обработчик ошибки 404
     * @return self Для цепочки вызовов
     */
    public function setNotFoundHandler($handler): self
    {
        $this->notFoundHandler = $handler;
        return $this;
    }

    /**
     * Внутренний метод добавления маршрута в список.
     * Нормализует путь и сохраняет маршрут.
     *
     * @param string $method HTTP-метод или '*' (любой)
     * @param string $path Путь маршрута
     * @param mixed $handler Обработчик
     * @return self Для цепочки вызовов
     */
    private function add(string $method, string $path, $handler): self
    {
        // Убираем слэши в начале и конце пути для единообразия
        $normalizedPath = trim($path, '/');

        // Сохраняем маршрут в общий список
        $this->routes[] = [
            'method' => $method,
            'path' => $normalizedPath,
            'handler' => $handler
        ];

        return $this;
    }

    /**
     * Запускает процесс маршрутизации.
     * Проходит по всем маршрутам и ищет совпадение по методу и пути.
     * При совпадении — вызывает обработчик с извлечёнными параметрами.
     * Если совпадений нет — вызывает обработчик 404.
     */
    public function run(): void
    {
        // Перебираем все зарегистрированные маршруты
        foreach ($this->routes as $route) {
            // Проверяем, подходит ли текущий запрос под этот маршрут
            if ($this->matches($route)) {
                // Извлекаем параметры из URI (например: id=123 из /user/123)
                $params = $this->extractParams($route['path'], $this->uri);

                // Вызываем обработчик маршрута с параметрами
                $this->call($route['handler'], $params);

                // Завершаем работу — маршрут найден
                return;
            }
        }

        // Ни один маршрут не подошёл — обрабатываем ошибку 404
        $this->handleNotFound();
    }

    /**
     * Проверяет, соответствует ли текущий запрос заданному маршруту.
     *
     * @param array $route Маршрут для проверки
     * @return bool true — если совпадает, иначе false
     */
    private function matches(array $route): bool
    {
        // Проверка HTTP-метода: '*' означает "любой метод"
        if ($route['method'] !== '*' && $route['method'] !== $this->method) {
            return false;
        }

        $routePath = $route['path'];
        $currentUri = $this->uri;

        // Точное совпадение путей (включая пустые строки — корень сайта)
        if ($routePath === $currentUri) {
            return true;
        }

        // Если маршрут содержит параметры (например: {id}), проверяем через регулярное выражение
        if (str_contains($routePath, '{')) {
            // Экранируем все специальные символы в пути для безопасного использования в регулярке
            $pattern = preg_quote($routePath, '/');

            // Заменяем каждый параметр вида {name} на шаблон ([^/]+) — "любые символы кроме слэша"
            $pattern = preg_replace('/\\\{[^\/]+\\\}/', '([^\/]+)', $pattern);

            // Добавляем привязку к началу (^) и концу ($) строки для точного совпадения
            $pattern = '#^' . $pattern . '$#';

            // Проверяем, соответствует ли текущий URI шаблону
            return (bool)preg_match($pattern, $currentUri);
        }

        // Ни точное совпадение, ни шаблон с параметрами не подошли
        return false;
    }

    /**
     * Извлекает именованные параметры из URI на основе шаблона маршрута.
     * Пример:
     *   маршрут: 'user/{id}/profile/{lang}'
     *   URI:     'user/42/profile/ru'
     *   результат: ['id' => '42', 'lang' => 'ru']
     *
     * @param string $routePath Шаблон маршрута
     * @param string $uri Текущий URI
     * @return array Ассоциативный массив параметров
     */
    private function extractParams(string $routePath, string $uri): array
    {
        // Если в маршруте нет фигурных скобок — параметров нет
        if (!str_contains($routePath, '{')) {
            return [];
        }

        // Извлекаем все имена параметров: из {id} получаем 'id'
        preg_match_all('/\{([^\/]+)\}/', $routePath, $matches);
        $paramNames = $matches[1]; // Массив имён: ['id', 'lang', ...]

        // Формируем регулярное выражение для извлечения значений
        $pattern = preg_quote($routePath, '/');
        $pattern = preg_replace('/\\\{[^\/]+\\\}/', '([^\/]+)', $pattern);
        $pattern = '#^' . $pattern . '$#';

        // Применяем регулярку к текущему URI
        if (preg_match($pattern, $uri, $values)) {
            // Убираем полное совпадение (первый элемент массива)
            array_shift($values);

            // Сопоставляем имена параметров со значениями
            return array_combine($paramNames, $values);
        }

        // Если не удалось извлечь — возвращаем пустой массив
        return [];
    }

    /**
     * Выполняет обработчик маршрута с переданными параметрами.
     * Поддерживает три типа обработчиков:
     *   1. Callable (анонимная функция, [объект, 'метод'])
     *   2. Строка вида 'Controller@method'
     *   3. Иначе — исключение.
     *
     * @param mixed $handler Обработчик маршрута
     * @param array $params Параметры для передачи в обработчик
     * @throws Exception Если обработчик недопустим
     */
    private function call($handler, array $params): void
    {
        // Случай 1: обработчик — callable (функция, замыкание, [obj, 'method'])
        if (is_callable($handler)) {
            // Передаём только значения параметров (без ключей), так как функция ожидает позиционные аргументы
            call_user_func_array($handler, array_values($params));
            return;
        }

        // Случай 2: обработчик — строка вида 'Controller@method'
        if (is_string($handler) && str_contains($handler, '@')) {
            // Разделяем строку на имя класса и метод
            [$class, $method] = explode('@', $handler, 2);

            // Проверяем, существует ли класс
            if (!class_exists($class)) {
                throw new Exception("Класс {$class} не найден");
            }

            // Создаём экземпляр контроллера
            $instance = new $class();

            // Проверяем, существует ли метод в этом классе
            if (!method_exists($instance, $method)) {
                throw new Exception("Метод {$method} не существует в классе {$class}");
            }

            // Вызываем метод контроллера с параметрами
            call_user_func_array([$instance, $method], array_values($params));
            return;
        }

        // Неподдерживаемый тип обработчика
        throw new Exception('Недопустимый обработчик маршрута');
    }

    /**
     * Обрабатывает ошибку 404: устанавливает HTTP-статус 404 и вызывает кастомный или стандартный обработчик.
     */
    private function handleNotFound(): void
    {
        // Устанавливаем HTTP-статус 404
        http_response_code(404);

        // Если задан кастомный обработчик — вызываем его
        if ($this->notFoundHandler !== null) {
            $this->call($this->notFoundHandler, []);
        } else {
            // Иначе выводим стандартное текстовое сообщение
            echo "404 - Страница не найдена";
        }
    }
}