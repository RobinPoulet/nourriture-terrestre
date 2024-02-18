<?php
require_once(__DIR__ . "/DateHelper.php");
class MenuManager {
    public const ERROR_DAY = "Pas le bon jour pour afficher le menu.";
    public const ERROR_MENU = "Pas de menu publié cette semaine.";
    public function __construct( // Utilisation nouvelle syntaxe simplifiée pour les constructeurs php 8
        private array $lastArticleLiElements,
        private array $menuHeader,
    ) {
    }
    public function canDisplayMenu($dateMenu) {
        $returnValue = false;
        // Date actuelle
        $currentDate = new DateTime();
        $formattedCurrentDate = $currentDate->format("Y-m-d");
                // Cas a vérifier si le current est dimanche est qu'il n'est pas égal à la date du menu, le nouveau menu n'a pas encore été publié
        if (
            (HelperDate::getCurrentDateWeekDay() === 7 && $dateMenu !== $formattedCurrentDate)
            || (HelperDate::getCurrentDateWeekDay() === 1 && HelperDate::dateDiff($formattedCurrentDate, $dateMenu) > 1)
        ) {
            throw new \Exception(self::ERROR_MENU);
        }
        // On peut afficher le menu le dimanche si le nouveau menu est publié
        // Ou le lundi avant 11h45
        if (
            $dateMenu === $formattedCurrentDate
            || (HelperDate::getCurrentDateWeekDay() === 1 && HelperDate::getCurrentDatetime() < 11 * 60 + 45)
        ) {
            $returnValue = true;
        } else {
            throw new \Exception(self::ERROR_DAY);
        }
        return $returnValue;
    }
    public function canDisplayMenuForm() {
        // On propose le formulaire de commande uniquement le lundi de 8h à 11h45
        $returnValue = false;
        if (
            HelperDate::getCurrentDateWeekDay() === 1
            && HelperDate::getCurrentDatetime() > 8 * 60
            && HelperDate::getCurrentDatetime() < 11 * 60 + 45
        ) {
            $returnValue = true;
        }
        return $returnValue;
    }
    public function getMenuArray() {
        $returnValue = [];
            foreach ($this->menuHeader as $index => $key) {
                $returnValue[$key] = $this->lastArticleLiElements[$index];
            }
        return $returnValue;
    }
}