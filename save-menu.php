<?php
require(__DIR__ . "/db-connexion.php");

// Obtenir la date actuelle
$dateActuelle = date("Y-m-d");

// Construire la requête SQL
$query = "SELECT COUNT(*) as nbEnregistrements FROM menu WHERE date <= DATE_SUB('$dateActuelle', INTERVAL 1 DAY)";

// Exécuter la requête
$resultat = $connexion->query($requete);

// Vérifier s'il y a des enregistrements avec une date inférieure ou égale à 1 jour
if ($resultat) {
    $row = $resultat->fetch_assoc();
    $nbEnregistrements = $row['nbEnregistrements'];

    // Vrai s'il existe des enregistrements, Faux sinon
    $existeEnregistrement = ($nbEnregistrements > 0) ? true : false;

    echo "Il " . ($existeEnregistrement ? "existe" : "n'existe pas") . " d'enregistrement avec une date inférieure ou égale à 1 jour.";
} else {
    echo "Erreur lors de l'exécution de la requête : " . $connexion->error;
}