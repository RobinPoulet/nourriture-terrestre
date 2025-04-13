<?php

namespace App\Controllers;

class AbstractController
{
    protected function render(string $view, array $data = [])
    {
        extract($data);
        ob_start();
        require dirname(__DIR__, 2) . "/views/$view.php";

        return ob_get_clean();
    }
}