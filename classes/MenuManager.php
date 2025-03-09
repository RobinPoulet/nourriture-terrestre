<?php

class MenuManager
{

    private Menus $menusEntity;
    private Dishes $dishesEntity;
    /**
     * Constructeur de la classe
     *
     * @param string $url Url du WordPress
     *
     * @return void
     * @throws Exception
     */
    public function __construct(private readonly WPContentManager $wpContentManager)
    {
        $this->menusEntity = new Menus();
        $this->dishesEntity = new Dishes();
    }

    /**
     * @throws Exception
     */
    public function buildMenu(): int
    {
        // On récupère la date du menu de la semaine
        $weekMenuDate = $this->wpContentManager->getLastPostDate();
        // On check si il y a déjà un menu qui existe à cette date
        $menu = $this->menusEntity->findByDate($weekMenuDate);
        $menuId = [];
        // Si pas de menu en base on va le créer
        if (is_null($menu)) {
            $imgSrc = $this->handleImgSrc();
            $figcaption = $this->wpContentManager->getFirstFigcaptionElement();
            $menuId = $this->menusEntity->insert($imgSrc, $figcaption, $weekMenuDate);
        }
        // Récupération de la liste des plats du menu de la semaine
        $tabDishesNames = $this->getLiElements();
        foreach ($tabDishesNames as $dishName) {
            //On check et update chaque plat s'il il existe en base de données
            $dishEntity = new Dishes();
            $dishElement = $dishEntity->findByName($dishName);
            if (is_null($dishElement)) {
                $dishEntity->insert($dishName, 1, $menuId["id"]);
            } else {
                // Sinon le plat existe déjà on incrémente le nombre de fois qu'il est présent dans le menu
                $total = (int)$dishElement["count"] + 1;
                $dishEntity->update($dishElement["id"], $total, $menuId["id"]);
            }
        }

        return $menuId["id"];
    }

    private function handleImgSrc(): string
    {
        $imageUrl = rawurldecode($this->wpContentManager->getFirstImgElement());
        $arrImageName = explode('/', $imageUrl);
        $imageName = array_pop($arrImageName);
        $returnValue = "./IMG/$imageName";
        // Téléchargement de l'image
        $imageContent = file_get_contents($imageUrl);
        if ($imageContent !== false) {
            // Sauvegarde de l'image localement
            file_put_contents($returnValue, $imageContent);
        }

        return $returnValue;
    }

}