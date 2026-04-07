<?php

namespace App\Manager;
use DateTime;
use DOMDocument;
use Exception;
use JsonException;

class WPContent
{

    /**
     * @var array Options de contexte pour le file get contents
     */
    private array $contextOptions = [
        'http' => [
            'method' => 'GET',
            'header' => 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3',
        ],
    ];

    /** @var ?object $lastPost Dernier Post */
    private ?object $lastPost;

    /** @var ?DOMDocument $doc Document dom element */
    private ?DOMDocument $doc;

    /**
     * Constructeur de la classe
     *
     * @param string $url Url du WordPress
     *
     * @return void
     * @throws Exception
     */
    public function __construct(private readonly string $url)
    {
        $this->lastPost = $this->getLastPost();
        $this->doc = new DOMDocument();
        $this->doc->loadHTML(
            '<?xml encoding="UTF-8"><div>' . $this->lastPost->content->rendered . '</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING
        );
    }

    /**
     * Récupérer le dernier article WordPress
     *
     * @return object
     * @throws Exception
     */
    private function getLastPost(): object
    {
        $requestQuery = '/wp-json/wp/v2/posts?per_page=1&order=desc&orderby=date';
        $apiEndpoint = $this->url . $requestQuery;
        $context = stream_context_create($this->contextOptions);
        $response = file_get_contents(
            $apiEndpoint,
            false,
            $context
        );

        try {
            $returnValue = json_decode($response, false, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            $returnValue = null;
        }

        return $returnValue[0];
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
     * Récupérer le premier attribut src de toutes les balises <img>
     *
     * @return string
     */
    public function getFirstImgElement(): string
    {
        $images = $this->doc->getElementsByTagName('img');

        return urlencode(($images[0]->getAttribute('src') ?? ''));
    }

    /**
     * Retourne le premier figcpation element
     *
     * @return string
     */
    public function getFirstFigcaptionElement(): string
    {
        $figcaptions = $this->doc->getElementsByTagName('figcaption');

        return $figcaptions[0]->nodeValue ?? '';
    }

    /**
     * Récupérer les élements de type <li></li> de l'article
     *
     *
     * @return array
     */
    public function getLiElements(): array
    {
        $returnValue = [];

        $lis = $this->doc->getElementsByTagName('li');
        foreach ($lis as $li) {
            $returnValue[] = $li->nodeValue;
        }

        return $returnValue;
    }

    /**
     * Récupérer la date du dernier article WordPress
     *
     * @return string
     *
     * @throws Exception
     */
    public function getLastPostDate(): string
    {
        $dateString = $this->lastPost->date;

        return (new DateTime($dateString))->format('Y-m-d');
    }

    public function isMenuThisWeek(): bool
    {
        return (
            isset($this->lastPost)
            && stripos($this->lastPost->title->rendered, 'menu') !== false
        );
    }
}
