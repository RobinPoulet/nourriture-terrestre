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
    public function __construct(private string $url) {}
    
    /**
    * Récupérer le dernier article WordPress
    *
    * @return array
    * @throws Exception Renvoie une exception si pas de réponse de l'API ou problème lors de la conversion du JSON
    */
    private function getLastPost(): array 
    {
        $returnValue = [];
        
        $requestQuery = "/wp-json/wp/v2/posts?per_page=1&order=desc&orderby=date";
        $apiEndpoint = $this->url.$requestQuery;
        $context = stream_context_create($this->contextOptions);
        $response = file_get_contents(
        $apiEndpoint,
        false,
        $context
        );
        $returnValue = $response 
        ? json_decode(
        $response,
        true,
        512,
        JSON_THROW_ON_ERROR
        ) 
        : [ "error" => "Erreur lors de la récupération des données de l'API Wordpress"];
        
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
        $images = $doc->getElementsByTagName("img");
        foreach ($images as $img) {
            $img->parentNode->removeChild($img);
        }
    }
    
    /**
     * Récupérer l'attribut src de toutes les balises <img>
     *
     * @param DOMDocument $doc
     *
     * @return array
     */
    private function getImgElements(DOMDocument $doc): array
    {
        $returnValue = [];
        
        $images = $doc->getElementsByTagName("img");
        // Parcourir toutes les balises <img> pour récupérer les URLs des images
        foreach ($images as $image) {
            // Récupérer l'attribut 'src' de la balise <img>
            $imageUrl = $image->getAttribute("src");
            // Ajouter l'URL de l'image au tableau
            $returnValue[] = $imageUrl;
        }
        
        return $returnValue;
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
        
        $lis = $doc->getElementsByTagName("li");
        foreach ($lis as $li) {
            $returnValue[] = $li->nodeValue;
        }
        
        return $returnValue;
    }
    
    /**
    * Récupérer la date du dernier article WordPress
    *
    * @return array<string, string>
    *
    */
    public function getLastPostDate(): array 
    {
        try {
            $lastArticle = $this->getLastPost();
            $dateString = $lastArticle[0]['date'];
            // Convertir la chaîne de date en objet DateTime
            $dateObj = new DateTime($dateString);
            // Formater la date selon le format "Y-m-d"
            $dateString = $dateObj->format("Y-m-d");
            return [
                "success" => $dateString
            ];
        } catch (\Exception $e) {
            return [
                "error" => "Erreur lors de la récupération du post : " . $e->getMessage()
            ];
        }
    }
    
    /**
    * Récupérer les éléments de type li de l'article
    *
    * @return array
    *
    */
    public function getLastPostLiElements(): array
    {
        try {
            $lastPost = $this->getLastPost();
            $doc = new DOMDocument();
            // Setup du loadHTML, pour utiliser les méthodes getElementsByName sans warning ni erreur
            $doc->loadHTML(
                '<?xml encoding="UTF-8"><div>' . $lastPost[0]['content']['rendered'] . '</div>', 
                LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING
            );
            $lis = $this->getLiElements($doc);
            // Si il n'y a pas de <li> dans l'article de la semaine, c'est une semaine sans menu on léve une exception
            if (empty($lis)) {
                // On récupère la source de l'image du message d'absence
                $tabAbsenceMessageImgSrc = $this->getImgElements($doc);
                $srcImage = "";
                if (!empty($tabAbsenceMessageImgSrc)) {
                    $srcImage .= urlencode($tabAbsenceMessageImgSrc[0]);
                  
                }
                Header("Location: bad-day.php".(empty($srcImage) ? "" : "?imgsrc=".$srcImage));
                die;
            }
            return [
                "success" => $lis
            ];
        } catch (\Exception $e) {
            return [
                "error" => $e->getMessage()
            ];
        }
    }
}