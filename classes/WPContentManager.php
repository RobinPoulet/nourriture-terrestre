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
     * Constructeur de la classe
     *
     * @param string $url Url du WordPress
     *
     * @return void
     */
    public function __construct( // Utilisation nouvelle syntaxe simplifiée pour les constructeurs php 8
        private string $url,
    ) {
    }
    
    /**
    * Récupérer le dernier article WordPress
    *
    * @return array
    * @throws Exception Renvoie une exception si pas de réponse de l'API ou problème lors de la conversion du JSON
    */
    private function getLastPost(): array 
    {
        $requestQuery = "/wp-json/wp/v2/posts?per_page=1&order=desc&orderby=date";
        $apiEndpoint = $this->url.$requestQuery;
        $context = stream_context_create($this->contextOptions);
        $response = file_get_contents($apiEndpoint, false, $context);

        if ($response === false) {
            throw new \Exception("Erreur dans la réponse de la requête.");
        }

        $postData = json_decode($response, true);

        if (is_null($postData)) {
            throw new \Exception("Erreur lors de la conversion JSON.");
        }

        return $postData;
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
        foreach ($liElements as $li) {
            $returnValue[] = $li->nodeValue;
        }
        return $returnValue;
    }
    
    /**
    * Récupérer la date du dernier article WordPress
    *
    * @return string
    * @throws Exception Fais remonter l'exception de l'appel à l'API 
    */
    public function getLastPostDate(): string 
    {
        $returnValue = "";
        try {
            $lastArticle = $this->getLastPost();
            $dateString = $lastArticle[0]['date'];
            // Convertir la chaîne de date en objet DateTime
            $dateObj = new DateTime($dateString);
            // Formater la date selon le format "Y-m-d"
            $returnValue = $dateObj->format("Y-m-d");
            return $returnValue;
        } catch (\Exception $e) {
            throw $e;
        }
    }
    
    /**
    * Récupérer les éléments de type <li></li> de l'article
    *
    * @return array
    * @throws Exception Fais remonter les exceptions aux méthodes qui vont utiliser cette méthode
    */

    public function getLastPostLiElements() {
        $returnValue = [];
        try {
            $lastPost = $this->getLastPost();
            $doc = new DOMDocument();
            // Setup du loadHTML, pour utiliser les méthodes getElementsByName sans warning ni erreur
            $doc->loadHTML(
                '<?xml encoding="UTF-8"><div>' . $lastPost[0]['content']['rendered'] . '</div>', 
                LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING
            );
            $returnValue = $this->getLiElements($doc);
            // Si il n'y a pas de <li> dans l'article de la semaine, c'est une semaine sans menu on léve une exception
            if (empty($returnValue)) {
               Header("Location: error-critic.html");
               die;
            }
            
            return $returnValue;
        } catch (\Exception $e) {
             // Lancez à nouveau l'exception pour propager l'erreur
             throw $e;
        }
    }
}