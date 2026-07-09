<?php

namespace App\Core;

abstract class Controller
{
    /**
     * Return a view representation
     */
    protected function view(string $view, array $data = [])
    {
        return View::render($view, $data);
    }

    /**
     * Redirect to a specific URL
     */
    protected function redirect(string $url)
    {
        header("Location: $url");
        exit;
    }

    /**
     * Return JSON response
     */
    protected function json($data, int $status = 200)
    {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }
}
