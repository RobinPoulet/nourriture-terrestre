<?php

namespace App\Controllers;

use App\DataFetcher\DataFetcher;
use Exception;

class HomeController extends AbstractController
{
    /**
     * @throws Exception
     */
    public function index()
    {
        $postData = DataFetcher::getData();
        $dishes = $postData["success"]["dishes"];
        $dateMenu = $postData["success"]["menu"]["CREATION_DATE"];
        $imgSrc = "./assets/IMG/".$postData["success"]["menu"]["IMG_SRC"];
        $figcaption = $postData["success"]["menu"]["IMG_FIGCAPTION"];

        return $this->render("home", [
            "dishes" => $dishes,
            "dateMenu" => $dateMenu,
            "imgSrc" => $imgSrc,
            "figcaption" => $figcaption,
        ]);
    }
}