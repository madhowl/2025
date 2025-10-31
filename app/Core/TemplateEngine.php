<?php

namespace App\Core;


class TemplateEngine
{
    private string $theme;
    private string $templatesDir;
    private array $blocks = [];
    private array $data = [];

    public function __construct(string $theme = 'default', string $templatesDir = 'templates')
    {
        $this->theme = $theme;
        $this->templatesDir = rtrim($templatesDir, '/') . '/';
    }

    public function setTheme(string $theme): void
    {
        $this->theme = $theme;
    }

    public function assign(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    public function render(string $templateName): void
    {
        $templatePath = $this->templatesDir . $this->theme . '/' . $templateName . '.php';

        if (!file_exists($templatePath)) {
            throw new Exception("Template not found: $templatePath");
        }

        // Сброс блоков перед рендером
        $this->blocks = [];

        // Извлекаем блоки из дочернего шаблона
        ob_start();
        include $templatePath;
        $childContent = ob_get_clean();

        // Если шаблон не наследует layout — просто выводим его
        if (!isset($this->blocks['__extends'])) {
            echo $childContent;
            return;
        }

        // Иначе рендерим layout
        $layoutPath = $this->templatesDir . $this->theme . '/' . $this->blocks['__extends'] . '.php';
        if (!file_exists($layoutPath)) {
            throw new Exception("Layout not found: $layoutPath");
        }

        // Передаём данные в layout
        extract($this->data);
        include $layoutPath;
    }

    // Функция для определения блока в дочернем шаблоне
    public function block(string $name, string $content = null): void
    {
        if ($content !== null) {
            // Вызов из layout: выводим содержимое блока или дефолт
            echo $this->blocks[$name] ?? $content;
        } else {
            // Вызов из дочернего шаблона: сохраняем буфер
            ob_start();
        }
    }

    public function endblock(): void
    {
        $content = ob_get_clean();
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $file = $trace[1]['file'];

        // Определяем имя блока из названия переменной в вызове block()
        // Это упрощённый способ — можно улучшить через стек или парсинг
        // Но для простоты будем использовать временный трюк:
        // Мы не можем точно узнать имя блока, поэтому будем использовать другой подход:
        // Вместо этого — сделаем block() принимать имя и замыкание
        // Но для совместимости с PHP без замыканий — см. альтернативу ниже
    }

    // Альтернативный подход: blockStart / blockEnd
    private ?string $currentBlock = null;

    public function blockStart(string $name): void
    {
        $this->currentBlock = $name;
        ob_start();
    }

    public function blockEnd(): void
    {
        if ($this->currentBlock) {
            $this->blocks[$this->currentBlock] = ob_get_clean();
            $this->currentBlock = null;
        }
    }

    // Для наследования
    public function extend(string $layout): void
    {
        $this->blocks['__extends'] = $layout;
    }
}