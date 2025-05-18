<?php

namespace App\Manager;
use App\Model\Menu;

abstract class Cache
{

    /** @var int Durée de validation du cache */
    private const int CACHE_VALIDITY_DURATION = 4 * 60 * 60;
    public const string NO_CACHE = "no cache";
    public const string SUCCESS_CACHE = "success cache";

    /**
     * Méthode pour vérifier si le cache est valide et le récupérer le cas échéant
     *
     * @return array
     */
    public static function getCache(): array
    {
        $returnValue[self::NO_CACHE] = "false";

        // On va chercher le dernier menu en base de données
        $lastMenu = Menu::last();

        if ($lastMenu !== null) {
            $lastUpdatedTimestamp = strtotime($lastMenu->modification_date ?? "0");
            if ((time() - $lastUpdatedTimestamp) <= self::CACHE_VALIDITY_DURATION) {
                // On met à jour la date de modification pour le cache
                $returnValue[self::SUCCESS_CACHE] = $lastMenu;
            }
        }

        return $returnValue;
    }
}
