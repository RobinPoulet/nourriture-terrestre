<?php
require(__DIR__ . "/classes/WPContentManager.php");
require(__DIR__ . "/classes/MenuManager.php");
$cacheFile = './cache/menu.json';

// Vérifier si le fichier de cache existe et est récent
if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 1 /* 86400 24 heures */) {
    // Charger les données à partir du cache
    $cachedData = file_get_contents($cacheFile);
    $postData = json_decode($cachedData, true);
} else {
    // On essaye de reconstruire le cache
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
         file_put_contents($cacheFile, json_encode($result));
         $postData = $result;
    } catch (\Exception $e) {
        $_SESSION["error_message"] = $e->getMessage();
        header("Location: error.php");
        exit;
    }
}
