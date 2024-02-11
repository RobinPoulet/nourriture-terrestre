<?php
abstract class  HelperDate {
    static public function getCurrentDateWeekDay() {
        $date = new DateTime();
        return (int) $date->format("N");
    }
    static public function getCurrentDatetime() {
        $date = new DateTime();
        return (int)$date->format("H") * 60 + (int)$date->format("i");
    }
    static public function dateDiff($date1_str, $date2_str) {
        // Convertir les chaînes de date en objets DateTime
        $date1 = new DateTime($date1_str);
        $date2 = new DateTime($date2_str);
        // Calculer la différence entre les deux dates
        $interval = $date1->diff($date2);
        // Obtenir le nombre de jours d'écart
        $days_diff = $interval->days;
        
        return $days_diff;
    }
}