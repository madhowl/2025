<?php

namespace App\Models;

use App\Core\FileManager;
use App\Traits\Helper;

class Post
{
    use Helper;
    public array $posts;
    protected FileManager $fm;

    public function __construct()
    {
        $this->fm = new FileManager();
    }

    /**
     * Получить все посты: и в корне posts/, и в категориях
     */
    public function getAllPosts($category = null): array
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


    public function getPostsInCategory($categorySlug)
    {
        $postsList = $this->getAllPosts($categorySlug);
        $posts = [];
        foreach ($postsList as $post) {
            $posts[] = $this->getPost($post);
        }
        return $posts;
    }
}