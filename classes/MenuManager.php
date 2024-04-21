<?php
abstract class MenuManager {
    /**
    * @var array Type de plat du menu
    */
    private static array $menuHeader = [
        "entree",
        "plat-1",
        "plat-2",
        "dessert-1",
        "dessert-2",
    ];

    /**
    * Avoir le menu de la semaine sous forme de tableau avec un clé le type de plat et en valeur son nom
    *
    * @param array $menuContent Liste des plats de la semaine
    *
    * @return array Le menu de la semaine clé : type de plat valeur : noms de plats
    */
    static public function getMenuArray(array $menuContent): array 
    {
        $returnValue = [];
        
        foreach ($menuContent as $index => $plat) {
            $returnValue[self::$menuHeader[$index]] = $plat;
        }
        
        return$returnValue;
    }
}