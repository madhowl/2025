<?php

namespace App\Core;


/**
 * FileManager — универсальный класс для работы с файловой системой.
 * Ограничен только папкой /content и /public/uploads (через upload.php).
 * Все пути нормализуются, чтобы избежать выхода за пределы разрешённых директорий.
 */
class FileManager
{
    private $contentDir;

    public function __construct($contentDir = __DIR__ . '/../../content')
    {
        // Приводим путь к абсолютному и убираем лишние слэши
        $this->contentDir = rtrim(realpath($contentDir), '/');
    }

    /**
     * Чтение файла из /content
     * @param string $path — относительный путь (например, 'pages/home.md')
     * @return string|false
     */
    public function read($path)
    {
        $fullPath = $this->contentDir . '/' . ltrim($path, '/');
        // Защита от path traversal
        if (strpos(realpath($fullPath), $this->contentDir) !== 0) {
            return false;
        }
        if (!file_exists($fullPath)) return false;
        return file_get_contents($fullPath);
    }

    /**
     * Запись файла в /content
     */
    public function write($path, $content)
    {
        $fullPath = $this->contentDir . '/' . ltrim($path, '/');
        if (strpos(realpath(dirname($fullPath)) ?: $fullPath, $this->contentDir) !== 0) {
            return false;
        }
        $dir = dirname($fullPath);
        if (!is_dir($dir)) mkdir($dir, 0777, true);
        return file_put_contents($fullPath, $content) !== false;
    }

    /**
     * Удаление файла
     */
    public function delete($path)
    {
        $fullPath = $this->contentDir . '/' . ltrim($path, '/');
        if (strpos(realpath($fullPath), $this->contentDir) !== 0) {
            return false;
        }
        return file_exists($fullPath) && unlink($fullPath);
    }

    /**
     * Список файлов с расширением в директории
     */
    public function listFiles($dir, $extension = '.md')
    {
        $fullDir = $this->contentDir . '/' . ltrim($dir, '/');
        if (!is_dir($fullDir)) return [];
        $files = glob($fullDir . '/*' . $extension);
        return array_map(function ($f) {
            return str_replace($this->contentDir . '/', '', $f);
        }, $files);
    }

    /**
     * Список поддиректорий
     */
    public function listDirs($dir)
    {
        $fullDir = $this->contentDir . '/' . ltrim($dir, '/');
        if (!is_dir($fullDir)) return [];
        return array_filter(glob($fullDir . '/*'), 'is_dir');
    }
}
