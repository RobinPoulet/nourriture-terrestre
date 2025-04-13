<?php

namespace App\Helper;
use DateMalformedStringException;
use DateTime;
use DateTimeImmutable;

abstract class  Date
{

    /**
     * Obtient le jour de la semaine actuel.
     *
     * @return int Le jour de la semaine actuel (1 pour lundi, 2 pour mardi, ..., 7 pour dimanche).
     */
    static public function getCurrentDateWeekDay(): int
    {
        $date = new DateTime();

        return (int)$date->format("N");
    }

    /**
     * Obtient la date et l'heure actuelles sous forme de minutes depuis minuit.
     *
     * @return int La date et l'heure actuelles sous forme de minutes depuis minuit.
     */
    static public function getCurrentDatetime(): int
    {
        $date = new DateTime();
        $hoursMinutes = (int)$date->format("H") * 60;
        $secondes = (int)$date->format("i");

        return $hoursMinutes + $secondes;
    }

    /**
     * Calcule la différence en jours entre deux dates.
     *
     * @param string $date1_str La première date au format Y-m-d.
     * @param string $date2_str La deuxième date au format Y-m-d.
     *
     * @return int Le nombre de jours d'écart entre les deux dates.
     * @throws DateMalformedStringException
     */
    static public function dateDiff(string $date1_str, string $date2_str): int
    {
        // Convertir les chaînes de date en objets DateTime
        $date1 = new DateTime($date1_str);
        $date2 = new DateTime($date2_str);
        // Calculer la différence entre les deux dates
        $interval = $date1->diff($date2);
        // Obtenir le nombre de jours d'écart
        $days_diff = $interval->days;

        return $days_diff;
    }

    /**
     * Vérifie si on peut afficher le formulaire de commande
     *
     * @param string $dateMenu Date de publication du menu
     *
     * @return bool Retourne true si il est possible d'afficher le formulaire de commande
     */
    static public function canDisplayOrderForm(string $dateMenu): bool
    {
        $returnValue = false;
        $currentDate = new DateTime();
        $formattedCurrentDate = $currentDate->format("Y-m-d");
        // On peut commander le lundi de 7h00 à 11h45 inclus, si il y a bien un menu publié cette semaine
        // Nouveau menu chaque dimanche, donc si il y a bien un menu cette semaine, la date du menu doit être inférieur ou égal à 1 par rapport à la date du jour
        if (
            self::getCurrentDateWeekDay() === 1
            && self::getCurrentDatetime() >= 6 * 60
            && self::getCurrentDatetime() <= 23 * 60 + 45
            && self::dateDiff($formattedCurrentDate, $dateMenu) <= 1
        ) {
            $returnValue = true;
        }

        return $returnValue;
    }

    /**
     * Vérifier si le menu est publié depuis moins d'une semaine
     *
     * @param string $dateMenu Date de publication du menu
     * @return boolean
     * @throws DateMalformedStringException
     */
    static public function isNewMenuAvailable(string $dateMenu): bool
    {
        $currentDate = new DateTimeImmutable();
        $storedDateTime = new DateTimeImmutable($dateMenu);
        // Calculer la différence entre les deux dates
        $interval = $currentDate->diff($storedDateTime);

        // Convertir la différence en jours
        $daysDifference = $interval->days;

        if (
            $currentDate->format("w") === "0"
            || $currentDate->format("w") === "1"
        ) {
            $returnValue = $daysDifference <= 8;
        } else {
            $returnValue = $daysDifference < 8;
        }

        return $returnValue;
    }

}