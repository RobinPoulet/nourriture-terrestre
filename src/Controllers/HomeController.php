<?php

namespace App\Controllers;

use App\DataFetcher\DataFetcher;
use App\Helper\Date;
use Exception;

class HomeController extends AbstractController
{
    /**
     * @throws Exception
     */
    public function index(): false|string
    {
        $data = DataFetcher::getData();
        $menu = $data['success'];
        $dateMenu = $menu->creation_date;
        $canDisplayForm = Date::canDisplayOrderForm($dateMenu);

        return $this->render('home', [
            'menu'           => $menu,
            'canDisplayForm' => $canDisplayForm,
        ]);
    }
}