<?php

namespace App\Manager;
use App\Entity\Dishes;
use App\Entity\Menus;

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
        $returnValue = [
            self::NO_CACHE => "false"
        ];

        // On va chercher le dernier menu en base de données
        $menusEntity = new Menus();
        $lastMenu = $menusEntity->getLastMenu();

        if ($lastMenu !== null) {
            $lastUpdatedTimestamp = strtotime($lastMenu["MODIFICATION_DATE"] ?? "0");
            if ((time() - $lastUpdatedTimestamp) > self::CACHE_VALIDITY_DURATION) {
                $menu = $menusEntity->findOneById($lastMenu["ID"]);
                $dishesEntity = new Dishes();
                $dishes = $dishesEntity->findByMenuId($lastMenu["ID"]);
                $returnValue = [
                    self::SUCCESS_CACHE => [
                        "menu" => $menu,
                        "dishes" => $dishes
                    ]
                ];
            }
        }

        return $returnValue;
    }
}
