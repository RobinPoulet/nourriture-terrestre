<?php

namespace App\Manager;
use App\Entity\Dishes;
use App\Entity\Menus;
use Exception;

class Menu
{

    private Menus $menusEntity;
    private Dishes $dishesEntity;
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
        $this->dishesEntity = new Dishes();
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

    private function handleImgSrc(): string
    {
        $imageUrl = rawurldecode($this->wpContentManager->getFirstImgElement());
        $arrImageName = explode('/', $imageUrl);
        $imageName = array_pop($arrImageName);
        $returnValue = $imageName;
        // Téléchargement de l'image
        $imageContent = file_get_contents($imageUrl);
        if ($imageContent !== false) {
            // Sauvegarde de l'image localement
            file_put_contents("assets/IMG/".$returnValue, $imageContent);
        }

        return $returnValue;
    }
}