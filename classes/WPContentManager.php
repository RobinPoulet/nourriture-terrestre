<?php
class WPContentManager {

    /**
    * @var array Options de contexte pour le file get contents
    */
    private array $contextOptions = [
        'http' => [
            'method' => 'GET',
            'header' => 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3',
        ],
    ];
    
    /**
    * @var string Paramètres de requêtes de l'API WordPress pour récupérer le dernier article
    */
    private string $lastPostRequestQuery = "/wp-json/wp/v2/posts?per_page=1&order=desc&orderby=date";
    
     /**
     * Constructeur de la classe
     *
     * @param string $url Url du WordPress
     *
     * @return void
     */
    public function __construct(
        private string $url,
    ) {
    }
    
    /**
    * Récupérer le dernier article WordPress
    *
    * @return array
    */
    private function getLastPost(): array 
    {
        $returnValue = [];
        
        $apiEndpoint = $this->url.$this->lastPostRequestQuery;
        $context = stream_context_create($this->contextOptions);
        $response = file_get_contents($apiEndpoint, false, $context);

        if ($response === false) {
            $returnValue["error"] = "Erreur dans la réponse de la requête.";
        } else {
            $postData = json_decode($response, true);

            if (is_null($postData)) {
                $returnValue["error"] = "Erreur lors de la conversion JSON.";
            } else {
                $returnValue["success"] = $postData;
            }
        }

        return $returnValue;
    }
    
    /**
    * Supprimer les images du contenu de l'article
    *
    * @param DOMDocument $doc Contenu de l'article parser en Dom Document
    *
    * @return void
    */
    public function deleteImages(DOMDocument $doc): void 
    {
        $images = $doc->getElementsByTagName('img');
        foreach ($images as $img) {
            $img->parentNode->removeChild($img);
        }
    }
    
    /**
    * Récupérer les élements de type <li></li> de l'article
    *
    * @param DOMDocument $doc Contenu de l'article parser en Dom Document
    *
    * @return array
    */
    private function getLiElements(DOMDocument $doc): array 
    {
        $returnValue = [];
        
        $liElements = $doc->getElementsByTagName('li');
        $returnValue = array_map(function ($li) {
            return $li->nodeValue;
        }, iterator_to_array($liElements));
        
        return $returnValue;
    }
    
    /**
    * Récupérer la date du dernier article WordPress
    *
    * @return array
    */
    public function getLastPostDate(): array
    {
        $returnValue = [];
        
        $lastPost = $this->getLastPost();
        if (isset($lastPost["error"])) {
            $returnValue["error"] = $lastPost["error"];
        } elseif (!isset($lastPost["success"][0]["date"])) {
            $returnValue["error"] = "Pas de date dans cet article";
        } else {
            $dateString = $lastPost["success"][0]['date'];
            // Convertir la chaîne de date en objet DateTime
            $dateObj = new DateTime($dateString);
            // Formater la date selon le format "Y-m-d"
            $returnValue["success"] = $dateObj->format("Y-m-d");
        }
           
        return $returnValue;
    }
    
    /**
    * Récupérer les éléments de type <li></li> de l'article
    *
    * @return array
    */

    public function getLastPostLiElements() {
        $returnValue = [];
        
        $lastPost = $this->getLastPost();
        if (isset($lastPost["error"])) {
            $returnValue["error"] = $lastPost["error"];
        } elseif (isset($lastPost[0]["content"]["rendered"])) {
            $returnValue["error"] = "Pas de contenu dans cet article";
        } else {
            $doc = new DOMDocument();
            // Setup du loadHTML, pour utiliser les méthodes getElementsByName sans warning ni erreur
            $doc->loadHTML(
                '<?xml encoding="UTF-8"><div>' . $lastPost["success"][0]['content']['rendered'] . '</div>', 
                LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING
            );
            $returnValue["success"] = $this->getLiElements($doc);
            // Si il n'y a pas de <li> dans l'article de la semaine, c'est une semaine sans menu on envoie la page erreur spéciale pas de nourriture terrestre
            if (empty($returnValue["success"])) {
               Header("Location: error-critic.html");
               die;
            }
        }
        
        return $returnValue;
    }
}