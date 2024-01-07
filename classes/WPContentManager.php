<?php
class WPContentManager {
    public const ERROR_RESPONSE = "Erreur dans la réponse de la requête.";
    public const ERROR_JSON = "Erreur lors de la conversion JSON.";
    public const MENU_NOT_AVAILABLE = "Pas de menu affiché dans l'article de cette semaine.";
    public function __construct( // Utilisation nouvelle syntaxe simplifiée pour les constructeurs php 8
        private string $url,
        private array $contextOptions,
    ) {
    }
    private function getLastPost() {
        $requestQuery = "/wp-json/wp/v2/posts?per_page=1&order=desc&orderby=date";
        $apiEndpoint = $this->url.$requestQuery;
        $context = stream_context_create($this->contextOptions);
        $response = file_get_contents($apiEndpoint, false, $context);

        if ($response === false) {
            throw new \Exception(self::ERROR_RESPONSE);
        }

        $postData = json_decode($response, true);

        if (is_null($postData)) {
            throw new \Exception(self::ERROR_JSON);
        }

        return $postData;
    }
    public function deleteImages(DOMDocument $doc): void {
        $images = $doc->getElementsByTagName('img');
        foreach ($images as $img) {
            $img->parentNode->removeChild($img);
        }
    }
    private function getLiElements(DOMDocument $doc): array {
        $returnValue = [];
        $liElements = $doc->getElementsByTagName('li');
        foreach ($liElements as $li) {
            $returnValue[] = $li->nodeValue;
        }
        return $returnValue;
    }
    
    
    public function getLastPostDate() {
        try {
            $lastArticle = $this->getLastPost();
            $dateString = $lastArticle[0]['date'];

            // Convertir la chaîne de date en objet DateTime
            $dateObj = new DateTime($dateString);

            // Formater la date selon le format "Y-m-d"
            $formattedDate = $dateObj->format("Y-m-d");

            // $formattedDate contient maintenant la date formatée
            return $formattedDate;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getLastPostLiElements() {
        try {
            $lastPost = $this->getLastPost();
            // Instanciation d'un DOCDocument, pour récupérer les éléments <li> du menu
            $doc = new DOMDocument();
            // Setup du loadHTML, pour utiliser les méthodes getElementsByName sans warning ni erreur
            $doc->loadHTML(
                '<?xml encoding="UTF-8"><div>' . $lastPost[0]['content']['rendered'] . '</div>', 
                LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING
            );
            $lastPostLiElements = $this->getLiElements($doc);
            if (count($lastPostLiElements) === 0) {
                throw new \Exception(self::MENU_NOT_AVAILABLE);
            }
            return $lastPostLiElements;
        } catch (\Exception $e) {
             // Lancez à nouveau l'exception pour propager l'erreur
             throw $e;
        }
    }
}