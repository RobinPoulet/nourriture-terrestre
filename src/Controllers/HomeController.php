<?php

namespace App\Controllers;

use App\DataFetcher\DataFetcher;
use Exception;

class HomeController extends AbstractController
{
    /**
     * @throws Exception
     */
    public function index(): false|string
    {
        $data = DataFetcher::getData();
        $menu = $data["success"];
        $isDishes = (!empty($menu->dishes()));

        return $this->render("home", [
            "menu"     => $menu,
            "isDishes" => $isDishes,
        ]);
    }
}