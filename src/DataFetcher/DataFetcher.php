<?php

namespace App\DataFetcher;

use App\Entity\Dishes;
use App\Entity\Menus;
use App\Manager\Cache;
use App\Manager\Menu;
use Exception;

abstract class DataFetcher
{
    private const string WP_URL = "http://www.nourriture-terrestre.fr";

    /**
     * Méthode pour récupérer les données à partir du cache ou les reconstruire
     *
     * @return array
     * @throws Exception
     */
    public static function getData(): array
    {
        $returnValue = [];

        $menuManager = new Menu(self::WP_URL);
        $cachedData = Cache::getCache();

        if (isset($cachedData[Cache::SUCCESS_CACHE])) {
            $data = $cachedData[Cache::SUCCESS_CACHE];
            // Vérification si l’image du menu existe localement
            $menu = $data["menu"] ?? null;
            if (isset($menu["IMG_SRC"])) {
                $imagePath = BASE_PATH."/assets/IMG/".$menu["IMG_SRC"];
                if (!file_exists($imagePath)) {
                    // Forcer le téléchargement de l'image si elle n'est pas présente
                    $menuManager->handleImgSrc();
                }
            }
            $returnValue["success"] = $cachedData[Cache::SUCCESS_CACHE];
        }

        if (isset($cachedData[Cache::NO_CACHE])) {
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
