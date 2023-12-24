<?php
require(__DIR__ . "/classes/WPContentManager.php");
$cacheFile = './cache/menu.json';

// Vérifier si le fichier de cache existe et est récent
if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 172800 /* 172800 48 heures */) {
    // Charger les données à partir du cache
    $cachedData = file_get_contents($cacheFile);
    $postData = json_decode($cachedData, true);
} else {
    $currentDay = date("N"); 
    // Vérifier si aujourd'hui est un dimanche ou un lundi
    if ($currentDay == 1 || $currentDay == 7) {
        $url = "http://www.nourriture-terrestre.fr";
        $contextOptions = [
            'http' => [
                'method' => 'GET',
                'header' => 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3',
            ],
        ];
        $menuHeader = [
            "entree",
            "plat 1",
            "plat 2",
            "dessert 1",
            "dessert 2",
        ];
        $wpContent = new WPContentManager($url, $contextOptions, $menuHeader);
        $dateMenu = $wpContent->getLastArticleDate();
        if ($dateMenu !== WPContentManager::ERROR_JSON && $dateMenu !== WPContentManager::ERROR_RESPONSE) {
            $menu = $wpContent->displayLastArticleMenu();
            if ($menu !== WPContentManager::MENU_NOT_AVAILABLE) {
                $result = [
                    "date" => $dateMenu,
                    "menu" => $menu,
                    "tata" => "tototot",
                    "titi" => "tatata",
                ];
                // Sauvegarder les données dans le cache
                file_put_contents($cacheFile, json_encode($result));
                $postData = $result;
            } else {
                 // Rediriger vers la page d'erreur
                 // TODO :: Créer une page d'erreur spécifique pour quand le menu n'est pas disponible
                 header("Location: error.php");
                 exit;
            }
        } else {
            // Rediriger vers la page d'erreur
            header("Location: error.php");
            exit;
        }
    } else {
        // Rediriger vers la page d'erreur de jour
        header("Location: error.php");
        exit;
    }
}
