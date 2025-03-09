<?php
abstract class CacheManager {

    /**
     * @var int Durée de validation du cache
     */
    private static int $cacheValidityDuration = 1;

    /**
     * Méthode pour vérifier si le cache est valide et le récupérer le cas échéant
     *
     * @return array
     */
    public static function getCache(): array
    {
        $returnValue = [
            "false" => "no cache"
        ];

        // On va chercher le dernier menu en base de données
        $menusEntity = new Menus();
        $lastMenu = $menusEntity->getLastMenu();

        if (
            $lastmenu !== null
            && (time() - filemtime(self::$cacheFile)) < self::$cacheValidityDuration
        ) {
            $returnValue = [
                "success" => json_decode(
                    file_get_contents(self::$cacheFile),
                    true
                )
            ];
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
