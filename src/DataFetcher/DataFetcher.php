<?php

namespace App\DataFetcher;

use App\Manager\Cache;
use App\Manager\MenuManager;
use Exception;

abstract class DataFetcher
{
    private const string WP_URL = 'http://www.nourriture-terrestre.fr';

    /**
     * Méthode pour récupérer les données à partir du cache ou les reconstruire
     *
     * @return array
     * @throws Exception
     */
    public static function getData(): array
    {
        $returnValue = [];

        $menuManager = new MenuManager(self::WP_URL);
        $cachedData = Cache::getCache();
        $menu = ($cachedData[Cache::SUCCESS_CACHE] ?? $menuManager->buildMenu());
        $returnValue['success'] = $menu;
        // Vérification si l’image du menu existe localement
        $imagePath = BASE_PATH . '/assets/IMG/' . $menu->img_src;
        if (!file_exists($imagePath)) {
            // Forcer le téléchargement de l'image si elle n'est pas présente
            $menuManager->handleImgSrc();
        }


        return $returnValue;
    }
}
