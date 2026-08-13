<?php

namespace App\Controller;


use App\Core\Controller;
use App\Core\View;
use App\Models\Post;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::getAllPublished();

        return View::renderTemplate('pages/public/blog/index', 'main', [
            'title' => 'Insights & News | Marigold Signature',
            'posts' => $posts
        ]);
    }

    public function show($slug)
    {
        $post = Post::findBySlug($slug);

        if (!$post) {
            http_response_code(404);
            return View::renderTemplate('pages/public/errors/404', 'main', [
                'title' => 'Article Not Found | Marigold Signature'
            ]);
        }

        return View::renderTemplate('pages/public/blog/show', 'main', [
            'title' => $post['title'] . ' | Insights | Marigold Signature',
            'post' => $post
        ]);
    }
}
