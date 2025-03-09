<?php
abstract class CacheManager {

    /** @var int $cacheValidityDuration Durée de validation du cache */
    private static int $cacheValidityDuration = 5 * 60 * 60;

    public const NO_CACHE = "no cache";
    public const SUCCESS_CACHE = "success cache";
    /**
     * Méthode pour vérifier si le cache est valide et le récupérer le cas échéant
     *
     * @return array
     */
    public static function getCache(): array
    {
        $returnValue = [
            self::NO_CACHE => 'false'
        ];

        // On va chercher le dernier menu en base de données
        $menusEntity = new Menus();
        $lastMenu = $menusEntity->getLastMenu();

        if ($lastMenu !== null) {
            $lastUpdatedTimestamp = strtotime($lastMenu['updated_at'] ?? '0');

            if ((time() - $lastUpdatedTimestamp) < self::$cacheValidityDuration) {
                $menu = $menusEntity->findOneById($lastMenu['ID']);
                $dishesEntity = new Dishes();
                $dishes = $dishesEntity->findByMenuId($lastMenu['ID']);
                $returnValue = [
                    self::SUCCESS_CACHE => [
                        "menu"   => $menu,
                        "dishes" => $dishes
                    ]
                ];
            }
        }

        return $returnValue;
    }


    /**
     * Méthode pour sauvegarder les données dans le cache
     *
     * @param array $data Données à save dans le cahce
     *
     * @return void
     */
    public static function saveCache(array $data): void
    {
        file_put_contents(
            self::$cacheFile,
            json_encode($data)
        );
    }
}
