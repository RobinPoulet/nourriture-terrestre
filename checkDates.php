<?php
function passerCommande($datePubliMenuNt, $dateCommande) {
    // Convertir la chaîne de date en objet DateTime
    $dateFormat = "Y-m-d\TH:i:s";
    $datePubliMenuNtFormat = DateTime::createFromFormat($dateFormat, $datePubliMenuNt);
    $dateCommandeFormat = DateTime::createFromFormat($dateFormat, $dateCommande);

    // Récupérer le jour de la semaine de l'autre date et l'heure de l'autre date
    $jourSemaineCommande = (int)$dateCommandeFormat->format('N'); // 1 (lundi) à 7 (dimanche)
    $heureCommande = (int)$dateCommandeFormat->format('H') * 60 + (int)$dateCommandeFormat->format('i'); // Convertir l'heure en minutes

    $returnValue = "";

    switch ($jourSemaineCommande) {
        case 1:
            if ($heureCommande < 11 * 60 + 45) {
                // Vérifier que $datePubliMenuNt est la veille de $dateCommande
                $dateCommandeVeille = clone $dateCommandeFormat;
                $dateCommandeVeille->modify('-1 day');

                if ($datePubliMenuNtFormat->format('Y-m-d') == $dateCommandeVeille->format('Y-m-d')) {
                    $returnValue = "Commande passée avec succès.";
                } else {
                    $returnValue = "Commande passée avec succès.";
                }
            } else {
                $returnValue = "Trop tard, commande passée.";
            }
            break;
        case 6:
        case 7:
            $returnValue = "C'est le weekend.";
            break;
        default:
            $returnValue = "Pas le bonjour pour commander.";
    }

    return $returnValue;
}