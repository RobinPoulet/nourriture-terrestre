<?php
require(__DIR__ . "/WPContentManager.php");
require(__DIR__ . "/MenuManager.php");
require(__DIR__ . "/CacheManager.php");

abstract class DataFetcher {

    /**
     * Méthode pour récupérer les données à partir du cache ou les reconstruire
     *
     * @return array
     * @throws Exception
     */
    public static function getData(): array
    {
        $returnValue = [];

        $cachedData = CacheManager::getCache();

        if (isset($cachedData[CacheManager::SUCCESS_CACHE])) {
            $returnValue["success"] = $cachedData[CacheManager::SUCCESS_CACHE];
        }

        if (isset($cachedData[CacheManager::NO_CACHE])) {
            $url = "http://www.nourriture-terrestre.fr";
            $menuManager = new MenuManager($url);
            $menuId = $menuManager->buildMenu();
            $menusEntity = new Menus();
            $menu = $menusEntity->findOneById($menuId);
            $dishesEntity = new Dishes();
            $dishes = $dishesEntity->findByMenuId($menuId);
            $returnValue["success"] = [
                "menu"   => $menu,
                "dishes" => $dishes
            ];
        }

        return $returnValue;
    }
}
