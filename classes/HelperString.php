<?php

abstract class  HelperString {

static public function shortString($string, $lengthMax) {
    $returnValue = "";
    // Vérifier si la chaîne est plus longue que la longueur maximale
    if (strlen($string) > $lengthMax) {
        // Couper la chaîne à la longueur maximale
        $returnValue = substr($string, 0, $lengthMax);
        // Vérifier si la chaîne coupée se termine déjà par "..."
        if (substr($returnValue, -3) !== '...') {
            // Ajouter "..." à la fin
            $returnValue .= '...';
        }
    } else {
        // Si la chaîne est déjà assez courte, la retourner telle quelle
        $returnValue = $string;
    }

    return $returnValue;
}

}
