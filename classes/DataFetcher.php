<?php
require(__DIR__ . "/WPContentManager.php");
require(__DIR__ . "/MenuManager.php");
require(__DIR__ . "/CacheManager.php");

abstract class DataFetcher {

    /**
     * Méthode pour récupérer les données à partir du cache ou les reconstruire
     *
     * @return array
     */
    public static function getData(): array 
    {
        $returnValue = [];
        
        $cachedData = CacheManager::getCache();
        if ($cachedData["success"]) {
            // Les données sont disponibles dans le cache
            $returnValue["success"] = $cachedData["success"];
        } else {
            // Les données ne sont pas disponibles dans le cache, on essaye de les reconstruire
            $url = "http://www.nourriture-terrestre.fr";
            $wpContent = new WPContentManager($url);
            $dateMenu = $wpContent->getLastPostDate();
            if (isset($dateMenu["success"])) {
                $menuContent = $wpContent->getLastPostLiElements();
                if (isset($menuContent["success"])) {
                    $menu = MenuManager::getMenuArray($menuContent["success"]);
                    $result = [
                        "date" => $dateMenu["success"],
                        "menu" => $menu,
                    ];
                    // Sauvegarder les données dans le cache
                    CacheManager::saveCache($result);
                    $returnValue["success"] = $result;
                } else {
                    $returnValue["error"] = $menuContent["error"]; 
                }
            } else {
                $returnValue["error"] = $dateMenu["error"];
            }
        }
        
        return $returnValue;
    }
}
