<?php
$cacheFile = './cache/menu.json';

// Vérifier si le fichier de cache existe et est récent
if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 172800 /* 172800 48 heures */) {
    // Charger les données à partir du cache
    $cachedData = file_get_contents($cacheFile);
    $postData = json_decode($cachedData, true);
} else {
    // Vérifier si aujourd'hui est un dimanche ou un lundi
    $currentDay = date("N"); // Retourne 1 pour lundi, 2 pour mardi, ..., 7 pour dimanche

    if ($currentDay == 1 || $currentDay == 7 || $currentDay == 6) {
        // Faire l'appel API
        $apiEndpoint = "http://www.nourriture-terrestre.fr/wp-json/wp/v2/posts?per_page=1&order=desc&orderby=date";
        $response = file_get_contents($apiEndpoint);

        if ($response !== false) {
            libxml_use_internal_errors(true);
            $postData = json_decode($response, true);

            if ($postData !== null) {
                $articleDate = $postData[0]['date'];

                $doc = new DOMDocument();
                $doc->loadHTML('<?xml encoding="UTF-8">' . $postData[0]['content']['rendered'], LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        
                $images = $doc->getElementsByTagName('img');
                foreach ($images as $img) {
                    $img->parentNode->removeChild($img);
                }
                // Récupérer le texte de tous les éléments <li>
                $liTextArray = array();
                $liElements = $doc->getElementsByTagName('li');
                foreach ($liElements as $li) {
                    $liTextArray[] = $li->nodeValue;
                }
                $menuKeyArray = [
                    "entree",
                    "plat 1",
                    "plat 2",
                    "dessert 1",
                    "dessert 2",
                ];
                $resultArray = [];
                foreach ($menuKeyArray as $index => $key) {
                    $resultArray[$key] = $liTextArray[$index];
                }
                libxml_use_internal_errors(false);
                $result = [
                    "date" => $articleDate,
                    "menu" => $resultArray,
            
                ];
                // Sauvegarder les données dans le cache
                file_put_contents($cacheFile, json_encode($result));

                libxml_use_internal_errors(false);
            } else {
                echo "Erreur lors de la conversion JSON.";
            }
        } else {
            // Rediriger vers la page d'erreur
            header("Location: error-page.php");
            exit;
        }
    } else {
        // Charger les données à partir du cache
        $cachedData = file_get_contents($cacheFile);
        $cacheData = json_decode($cachedData, true);
    }
}