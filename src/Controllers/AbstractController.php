<?php

namespace App\Controllers;

class AbstractController
{
    protected function render(string $view, array $data = [], string $layout = 'layout'): false|string
    {
        extract($data);

        ob_start();
        require dirname(__DIR__, 2) . "/views/$view.php";
        $content = ob_get_clean();

        ob_start();
        require dirname(__DIR__, 2) . "/views/$layout.php";
        return ob_get_clean();
    }

}