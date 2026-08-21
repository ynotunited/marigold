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
     * Redirect to a URL. Applies the app base path so redirects work when the
     * app is served from a subdirectory (e.g. /ms on shared hosting).
     * Rejects CR/LF to prevent header injection.
     */
    protected function redirect(string $url)
    {
        if (preg_match('/[\r\n]/', $url)) {
            $url = '/';
        }

        $base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
        if ($base !== '' && $base !== '/' && stripos($url, $base) !== 0) {
            $url = $base . $url;
        }

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
