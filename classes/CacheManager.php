<?php
abstract class CacheManager {
    private static $cacheFile = './cache/menu.json';

    // Méthode pour vérifier si le cache est valide et le récupérer le cas échéant
    public static function getCache($validityDuration) {
        if (file_exists(self::$cacheFile) && (time() - filemtime(self::$cacheFile)) < $validityDuration) {
            return json_decode(file_get_contents(self::$cacheFile), true);
        }
        return false;
    }

    // Méthode pour sauvegarder les données dans le cache
    public static function saveCache($data) {
        file_put_contents(self::$cacheFile, json_encode($data));
    }
}
