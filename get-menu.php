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
    $contextOptions = [
        'http' => [
            'method' => 'GET',
            'header' => 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3',
        ],
    ];
    $wpContent = new WPContentManager($url, $contextOptions);
    try {
        $dateMenu = $wpContent->getLastPostDate();
        $lastPostLiElements = $wpContent->getLastPostLiElements();
        $menuHeader = [
            "entree",
            "plat 1",
            "plat 2",
            "dessert 1",
            "dessert 2",
        ];
        $menuManager = new MenuManager($lastPostLiElements, $menuHeader);
        if ($menuManager->canDisplayMenu($dateMenu)) {
            $menu = $menuManager->getMenuArray();
            $result = [
                "date" => $dateMenu,
                "menu" => $menu,
            ];
             // Sauvegarder les données dans le cache
             file_put_contents($cacheFile, json_encode($result));
             $postData = $result;
        }
    } catch (\Exception $e) {
        $_SESSION["error_message"] = $e->getMessage();
        header("Location: error.php");
        exit;
    }
}
