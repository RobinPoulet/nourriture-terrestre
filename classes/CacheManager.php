<?php
abstract class CacheManager {
    /**
     * @var string Path vers le fichier json de cache pour sauvegarder les données du menu de la semaine
     */
    private static $cacheFile = './cache/menu.json';
    /**
     * @var int Durée de validation du cache
     */
    private static $cacheValidityDuration = 2;
    
    /**
     * Récupérer la durée de validation du cache
     *
     * @return int Durée de validation du cache
     */
    public static function getcacheValidityDuration(): int
    {
        return self::$cacheValidityDuration;
    }
    
    /**
     * Mettre à jour la durée de validation du cache
     *
     * @param integer $duration durée de validation du cache
     *
     * @return void
     */
    public static function setCacheValidityDuration(int $duration): void
    {
        self::$cacheValidityDuration = $duration;
    }

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
        
        if (
            file_exists(self::$cacheFile)
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
