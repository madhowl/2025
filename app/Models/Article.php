<?php

namespace App\Models;
use App\Core\FileManager;

class Article
{
    public array $articles;
    protected FileManager $fm;

    public function __construct()
    {
        $this->fm = new FileManager();
        $this->articles = [
            [
                'title' => 'Title 1',
                'content' => 'Content 1'
            ],
            [
                'title' => 'Title 2',
                'content' => 'Content 2'
            ],
            [
                'title' => 'Title 3',
                'content' => 'Content 3'
            ]
        ];
    }

    public function all()
    {
        return $this->articles;
    }
    /**
     * Получить все посты: и в корне posts/, и в категориях
     */
    public function getAllArticles($category = null): array
    {
        if ($category) {
            return $this->fm->listFiles("posts/{$category}");
        }
        $posts = $this->fm->listFiles('posts');
        $categories = $this->getCategories();
        foreach ($categories as $cat) {
            $posts = array_merge($posts, $this->fm->listFiles("posts/{$cat}"));
        }
        return array_unique($posts);
    }

    public function getCategories() {
        $dirs = $this->fm->listDirs('posts');
        return array_map(function($d) {
            return basename($d);
        }, $dirs);
    }

    public function getPost($path) {
        $content = $this->fm->read($path);
        if (!$content) return null;
        $parts = explode("\n---\n", $content, 2);
        $meta = json_decode($parts[0], true) ?: [];
        return ['meta' => $meta, 'body' => $parts[1] ?? ''];
    }


}