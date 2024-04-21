<?php
require(__DIR__ . "/WPContentManager.php");
require(__DIR__ . "/MenuManager.php");
require(__DIR__ . "/CacheManager.php");

abstract class DataFetcher {
    private static $validityDuration = 100;
    // Méthode pour récupérer les données à partir du cache ou les reconstruire
    public static function getData() {
        $returnValue = [];
        
        $cachedData = CacheManager::getCache(self::$validityDuration);
        // var_dump($cachedData);
        if ($cachedData) {
            // Les données sont disponibles dans le cache
            $returnValue["success"] =  $cachedData;
        } else {
            // Les données ne sont pas disponibles dans le cache, on essaye de les reconstruire
            $url = "http://www.nourriture-terrestre.fr";
            $wpContent = new WPContentManager($url);
            $dateMenu = $wpContent->getLastPostDate();
            if (isset($dateMenu["error"])) {
                $returnValue["error"] = $dateMenu["error"];
            } else {
                $menuContent = $wpContent->getLastPostLiElements();
                if (isset($menuContent["error"])) {
                    $returnValue["error"] = $menuContent["error"];
                } else {
                    $menu = MenuManager::getMenuArray($menuContent["success"]);
                    $result = [
                        "date" => $dateMenu["success"],
                        "menu" => $menu,
                    ];
                    // Sauvegarder les données dans le cache
                    CacheManager::saveCache($result);
                    $returnValue["success"] = $result;
                }
            }
        }
        var_dump($returnValue);
        return $returnValue;
    }
}
