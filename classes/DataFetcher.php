<?php
require(__DIR__ . "/WPContentManager.php");
require(__DIR__ . "/MenuManager.php");
require(__DIR__ . "/CacheManager.php");

abstract class DataFetcher {

    // Méthode pour récupérer les données à partir du cache ou les reconstruire
    public static function getData() 
    {
        $cachedData = CacheManager::getCache();
        if ($cachedData) {
            // Les données sont disponibles dans le cache
            return $cachedData;
        } else {
            // Les données ne sont pas disponibles dans le cache, on essaye de les reconstruire
            $url = "http://www.nourriture-terrestre.fr";
            $wpContent = new WPContentManager($url);
            try {
                $dateMenu = $wpContent->getLastPostDate();
                $menuContent = $wpContent->getLastPostLiElements();
                $menu = MenuManager::getMenuArray($menuContent);
                $result = [
                    "date" => $dateMenu,
                    "menu" => $menu,
                ];
                // Sauvegarder les données dans le cache
                CacheManager::saveCache($result);
                return $result;
            } catch (\Exception $e) {
                // En cas d'erreur, rediriger vers une page d'erreur
                throw new Exception("Erreur lors de la récupération des données : " . $e->getMessage());
            }
        }
    }
}
