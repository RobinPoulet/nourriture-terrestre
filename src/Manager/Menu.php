<?php

namespace App\Manager;
use App\Entity\Dishes;
use App\Entity\Menus;
use Exception;

class Menu
{
    private Menus $menusEntity;
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
        $this->menusEntity = new Menus();
        $this->wpContentManager = new WPContent($this->wpUrl);
    }

    /**
     * @throws Exception
     */
    public function buildMenu(): int
    {
        // On récupère la date du menu de la semaine
        $weekMenuDate = $this->wpContentManager->getLastPostDate();
        // On check si il y a déjà un menu qui existe à cette date
        $menu = $this->menusEntity->findOneByDate($weekMenuDate);
        // Si pas de menu en base on va le créer
        if (is_null($menu)) {
            $imgSrc = $this->handleImgSrc();
            $figcaption = $this->wpContentManager->getFirstFigcaptionElement();
            // On vérifie si il y a bien un menu cette semaine
            $isMenuThisWeek = $this->wpContentManager->isMenuThisWeek();
            $menuId = $this->menusEntity->insert($imgSrc, $figcaption, $isMenuThisWeek, $weekMenuDate);
        } else {
            $menuId = $menu["ID"];
            $isMenuThisWeek = $menu["IS_OPEN"];
        }

        if ($isMenuThisWeek) {
            // Récupération de la liste des plats du menu de la semaine
            $tabDishesNames = $this->wpContentManager->getLiElements();
            foreach ($tabDishesNames as $dishName) {
                //On check et update chaque plat s'il il existe en base de données
                $dishEntity = new Dishes();
                $dishElement = $dishEntity->findOneByName($dishName);
                if (is_null($dishElement)) {
                    $dishEntity->insert($dishName, 1, $menuId);
                } else {
                    // Sinon le plat existe déjà on incrémente le nombre de fois qu'il est présent dans le menu
                    $total = (int)$dishElement["TOTAL"] + 1;
                    $dishEntity->update($dishElement["ID"], $total, $menuId);
                }
            }
        }

        return $menuId;
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
            error_log("❌ Invalid image URL: " . $imageUrl);
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
            CURLOPT_TIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        $imageContent = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (
            $imageContent === false
            || $httpCode !== 200
        ) {
            error_log("❌ Failed to download image from: $imageUrl (HTTP $httpCode)");
            return false;
        }

        // Sauvegarde
        if (file_put_contents($savePath, $imageContent) === false) {
            error_log("❌ Failed to save image to $savePath");
            return false;
        } else {
            chmod($savePath, 0644);
        }

        return $imageName;
    }
}