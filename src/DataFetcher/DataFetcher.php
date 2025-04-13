<?php

namespace App\DataFetcher;

use App\Entity\Dishes;
use App\Entity\Menus;
use App\Manager\Cache;
use App\Manager\Menu;
use Exception;

abstract class DataFetcher
{

    /**
     * Méthode pour récupérer les données à partir du cache ou les reconstruire
     *
     * @return array
     * @throws Exception
     */
    public static function getData(): array
    {
        $returnValue = [];

        $cachedData = Cache::getCache();

        if (isset($cachedData[Cache::SUCCESS_CACHE])) {
            $returnValue["success"] = $cachedData[Cache::SUCCESS_CACHE];
        }

        if (isset($cachedData[Cache::NO_CACHE])) {
            $url = "http://www.nourriture-terrestre.fr";
            $menuManager = new Menu($url);
            $menuId = $menuManager->buildMenu();
            $menusEntity = new Menus();
            $menu = $menusEntity->findOneById($menuId);
            $dishesEntity = new Dishes();
            $dishes = $dishesEntity->findByMenuId($menuId);
            $returnValue["success"] = [
                "menu" => $menu,
                "dishes" => $dishes
            ];
        }

        return $returnValue;
    }
}
