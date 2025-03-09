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
        if (isset($cachedData["success"]) && $cachedData["success"]) {
            // Les données sont disponibles dans le cache
            $returnValue["success"] = $cachedData["success"];
        } else {
            // Les données ne sont pas disponibles dans le cache, on essaye de les reconstruire
            $url = "http://www.nourriture-terrestre.fr";
            $wpContent = new WPContentManager($url);
            $menuManager = new MenuManager($wpContent);
            $menuManager->buildMenu();
        }

        return $returnValue;
    }
}
