<?php
class WPContentManager {
    public const ERROR_RESPONSE = "Erreur dans la réponse de la requête.";
    public const ERROR_JSON = "Erreur lors de la conversion JSON.";
    public const MENU_NOT_AVAILABLE = "Pas de menu affcihé dans l'article de cette semaine.";
    public function __construct( // Utilisation nouvelle syntaxe simplifiée pour les constructeurs php 8
        private string $url,
        private array $contextOptions,
        private array $menuHeader,
    ) {
    }
    private function hasLiElement(DOMDocument $doc): bool {
        $liElements = $doc->getElementsByTagName('li');
        return $liElements->length > 0;
    }
    private function deleteImages(DOMDocument $doc): void {
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
    private function getLastArticle() {
        $returnValue = "";
        $requestQuery = "/wp-json/wp/v2/posts?per_page=1&order=desc&orderby=date";
        $apiEndpoint = $this->url.$requestQuery;
        $context = stream_context_create($this->contextOptions);
        $response = file_get_contents($apiEndpoint, false, $context);
        if ($response !== false) {
            $postData = json_decode($response, true);
            if ($postData !== null) {
                $returnValue = $postData;
            } else {
                $returnValue = self::ERROR_JSON;
            }
        } else {
            $returnValue = self::ERROR_RESPONSE;
        }
        return $returnValue;
    }
    
    public function getLastArticleDate() {
        $returnValue = "";
        $lastArticle = $this->getLastArticle();
        if (is_array($lastArticle)) {
            $returnValue = $lastArticle[0]['date'];
        } else {
            $returnValue = $lastArticle;
        }
        return $returnValue;
    }

    private function getLastArticleMenuData() {
        $returnValue = "";
        $lastArticle = $this->getLastArticle();
        if (is_array($lastArticle)) {
            // Instanciation d'un DOCDocument, pour récupérer les éléments <li> du menu
            $doc = new DOMDocument();
            // Setup du loadHTML, pour utiliser les méthodes getElementsByName sans warning ni erreur
            $doc->loadHTML('<?xml encoding="UTF-8"><div>' . $lastArticle[0]['content']['rendered'] . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING);
            if ($this->hasLiElement($doc)) {
                $returnValue = $this->getLiElements($doc);
            } else {
                $returnValue = self::MENU_NOT_AVAILABLE;
            }
        } else {
            $returnValue = $lastArticle;
        }
        return $returnValue;
    }
    
    public function displayLastArticleMenu() {
        $returnValue = [];
        $menuData = $this->getLastArticleMenuData();
        if (is_array($menuData)) {
            foreach ($this->menuHeader as $index => $key) {
                $returnValue[$key] = $menuData[$index];
            }
        } else {
            // $menuData pas un tableau, on a une constante d'erreur
            $returnValue = $menuData;
        }
        return $returnValue;
    }
}
