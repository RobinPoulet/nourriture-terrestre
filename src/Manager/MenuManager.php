<?php

namespace App\Manager;
use App\Model\Dish;
use App\Model\Menu;
use Exception;

class MenuManager
{
   /** @var WPContent $wpContentManager Gestionnaire de l'API Wordpress */
    private WPContent $wpContentManager;

    /**
     * Constructeur de la classe
     *
     * @param string $wpUrl Url du WordPress
     *
     * @return void
     * @throws Exception
     */
    public function __construct(private readonly string $wpUrl)
    {
        $this->wpContentManager = new WPContent($this->wpUrl);
    }

    /**
     * Construction du menu
     *
     * @throws Exception
     */
    public function buildMenu(): ?Menu
    {
        $weekMenuDate = $this->wpContentManager->getLastPostDate();
        // On check en base si il y a déjà un menu qui existe à cette date
        $menu = Menu::query()
            ->where("creation_date", "=", $weekMenuDate)
            ->first();

        // Si pas de menu en base on va le créer
        if (is_null($menu)) {
            $imgSrc = $this->handleImgSrc();
            $figcaption = $this->wpContentManager->getFirstFigcaptionElement();
            $isMenuThisWeek = $this->wpContentManager->isMenuThisWeek();
            $menu = Menu::create([
                "img_src"        => $imgSrc,
                "img_figcaption" => $figcaption,
                "is_open"        => $isMenuThisWeek,
                "creation_date"  => $weekMenuDate
            ]);
        }

        if ($menu->is_open) {
            // Récupération de la liste des plats du menu de la semaine
            $tabDishesNames = $this->wpContentManager->getLiElements();
            foreach ($tabDishesNames as $dishName) {
                //On check et update chaque plat s'il il existe en base de données
                $dishElement = Dish::query()
                    ->where("name", "=", $dishName)
                    ->first();
                if (is_null($dishElement)) {
                    $newDish = Dish::create([
                        "name"          => $dishName,
                        "total"         => 1,
                        "menu_id"       => $menu->id,
                        "creation_date" => $weekMenuDate,
                    ]);
                } else {
                    // Sinon le plat existe déjà on incrémente le nombre de fois qu'il est présent dans le menu
                    $total = (int)$dishElement->total + 1;
                    Dish::update($dishElement->id, ["total" => $total, "menu_id" => $menu->id]);
                }
            }
        }

        return $menu;
    }


    /**
     * Récupérer l'image du menu
     *
     * @return false|string
     */
    public function handleImgSrc(): false|string
    {
        $imageUrl = rawurldecode($this->wpContentManager->getFirstImgElement());

        if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            error_log("❌ Invalid image URL: " . $imageUrl, 3, BASE_PATH . "/logs/error.log");
            return false;
        }

        $arrImageName = explode("/", $imageUrl);
        $imageName = array_pop($arrImageName);
        $savePath = BASE_PATH . "/assets/IMG/" . $imageName;

        // Utiliser cURL pour plus de robustesse
        $ch = curl_init($imageUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        $imageContent = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (
            $imageContent === false
            || $httpCode !== 200
        ) {
            error_log("❌ Failed to download image from: $imageUrl (HTTP $httpCode)", 3, BASE_PATH . "/logs/error.log");
            return false;
        }

        // Sauvegarde
        if (file_put_contents($savePath, $imageContent) === false) {
            error_log("❌ Failed to save image to $savePath", 3, BASE_PATH . "/logs/error.log");
            return false;
        } else {
            chmod($savePath, 0644);
        }

        return $imageName;
    }
}