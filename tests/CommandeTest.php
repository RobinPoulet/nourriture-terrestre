<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../checkDates.php';

use PHPUnit\Framework\TestCase;

class CommandeTest extends TestCase {
    public function testCommandeBonJourBonneHeure() {
        $dateDimanche = "2023-11-26T10:30:00";
        $dateAutreJour = "2023-11-27T10:30:00";
        $this->assertEquals("Commande passée avec succès.", passerCommande($dateDimanche, $dateAutreJour));
    }

    public function testCommandeBonJourHeureTropTard() {
        $dateDimanche = "2023-11-26T15:00:00";
        $dateAutreJour = "2023-11-27T16:30:00";
        $this->assertEquals("Trop tard, commande passée.", passerCommande($dateDimanche, $dateAutreJour));
    }

    public function testCommandeWeekEnd() {
        $dateDimanche = "2023-11-26T13:00:00";
        $dateAutreJour = "2023-11-26T13:00:00";
        $this->assertEquals("C'est le weekend.", passerCommande($dateDimanche, $dateAutreJour));
    }

    public function testCommandePasLeBonJour() {
        $dateLundi = "2023-11-27T10:00:00";
        $dateAutreJour = "2023-11-29T13:00:00";
        $this->assertEquals("Pas le bonjour pour commander.", passerCommande($dateLundi, $dateAutreJour));
    }
    
    public function testPasdeMenuPubliéLaVeille() {
        $datePubliNourritureTerrestre = "2023-11-19T10:00:00";
        $dateAutreJour = "2023-11-27T10:30:00";
        $this->assertEquals("Pas de nourriture terrestre cette semaine", passerCommande($datePubliNourritureTerrestre, $dateAutreJour));
    }
}