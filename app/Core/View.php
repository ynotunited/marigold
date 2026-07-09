<?php

namespace App\Core;

class View
{
    /**
     * Render a view file
     *
     * @param string $view  The view file path (e.g. 'pages/public/home')
     * @param array $args   Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function render(string $view, array $args = []): void
    {
        extract($args, EXTR_SKIP);

        $file = BASE_PATH . "/app/View/$view.php";

        if (is_readable($file)) {
            require $file;
        } else {
            throw new \Exception("View $file not found");
        }
    }

    /**
     * Render a view using a layout
     */
    public static function renderTemplate(string $view, string $layout = 'public', array $args = []): void
    {
        // Layouts should have a variable $content where the view will be rendered
        ob_start();
        self::render($view, $args);
        $content = ob_get_clean();

        $args['content'] = $content;
        self::render("layouts/$layout", $args);
    }
}
