<?php
require(__DIR__ . "/db-connexion.php");

$apiEndpoint = "http://www.nourriture-terrestre.fr/wp-json/wp/v2/posts?per_page=1&order=desc&orderby=date";

$response = file_get_contents($apiEndpoint);

if ($response !== false) {
    libxml_use_internal_errors(true);
    $postData = json_decode($response, true);

    if ($postData !== null) {
        $articleDate = $postData[0]['date'];

        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $postData[0]['content']['rendered'], LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        // Supprimer l'image de l'article récupéré
        $images = $doc->getElementsByTagName('img');
        foreach ($images as $img) {
            $img->parentNode->removeChild($img);
        }
        // Récupérer le texte de tous les éléments <li>
        $liTextArray = array();
        $liElements = $doc->getElementsByTagName('li');
        foreach ($liElements as $li) {
            $liTextArray[] = $li->nodeValue;
        }
        $menuKeyArray = [
            "entree",
            "plat 1",
            "plat 2",
            "dessert 1",
            "dessert 2",
        ];
        $resultArray = [];
        foreach ($menuKeyArray as $index => $key) {
            $resultArray[$key] = $liTextArray[$index];
        }
    } else {
        echo "Erreur lors de la conversion JSON.";
    }
} else {
   // Rediriger vers la page "error-page.html" si pas de retour de l'api, sinon la page ne pas fonctionner
   // TODO enregistrer le menu en BDD pour faire l'appel à l'API qu'une seule fois
    header("Location: error-page.php");
    die();
}

function raccourcirChaine($chaine, $longueurMax) {
    // Vérifier si la chaîne est plus longue que la longueur maximale
    if (strlen($chaine) > $longueurMax) {
        // Couper la chaîne à la longueur maximale
        $chaineCoupee = substr($chaine, 0, $longueurMax);

        // Vérifier si la chaîne coupée se termine déjà par "..."
        if (substr($chaineCoupee, -3) !== '...') {
            // Ajouter "..." à la fin
            $chaineCoupee .= '...';
        }

        return $chaineCoupee;
    }

    // Si la chaîne est déjà assez courte, la retourner telle quelle
    return $chaine;
}
$currentDate = date("Y-m-d");
 $query = "SELECT * FROM orders WHERE creation_date = :creation_date";
 $stmt = $pdo->prepare($query);
 $stmt->bindParam(':creation_date', $currentDate);
 $stmt->execute();
 $resultsOrder = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST["ajax"]) && $_POST["ajax"] === "deleteOrder") {
    $id = intval($_POST["deleteId"]);
    $query = "DELETE FROM orders WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    echo json_encode(["deleted" => $id]);
    die();
}

 $currentDate = date("Y-m-d");
 $query = "SELECT * FROM orders WHERE creation_date = :creation_date";
 $stmt = $pdo->prepare($query);
 $stmt->bindParam(':creation_date', $currentDate);
 $stmt->execute();
 $resultsOrder = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
 ?>
 <!DOCTYPE html>
<html>

<?php require(__DIR__ . "/head.html"); ?>

<body>
</div>
    <div class="container">

    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Nom</th>
                <th scope="col"><?= raccourcirChaine($resultArray["entree"], 18) ?></th>
                <th scope="col"><?= raccourcirChaine($resultArray["plat 1"], 18) ?></th>
                <th scope="col"><?= raccourcirChaine($resultArray["plat 2"], 18) ?></th>
                <th scope="col"><?= raccourcirChaine($resultArray["dessert 1"], 18) ?></td>
                <th scope="col"><?= raccourcirChaine($resultArray["dessert 2"], 18) ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php
        $totalOrders = [
            "entree" => 0,
            "plat-1" => 0,
            "plat-2" => 0,
            "dessert-1" => 0,
            "dessert-2" => 0,
        ];
            
        foreach ($resultsOrder as $result) {
            $decodedJson = json_decode($result["CONTENT"]);
            $orders = [];
            foreach ($decodedJson as $value) {
                $orders[] = $value;
                $totalOrders[$value]++;
            }
            //var_dump($totalOrders);
            echo "
                <tr id=\"tr".$result["ID"]."\">
                    <td>".$result["NAME"]."</td>
                    <td>".(in_array("entree", $orders) ? "X" : "")."</td>
                    <td>".(in_array("plat-1", $orders) ? "X" : "")."</td>
                    <td>".(in_array("plat-2", $orders) ? "X" : "")."</td>
                    <td>".(in_array("dessert-1", $orders) ? "X" : "")."</td>
                    <td>".(in_array("dessert-2", $orders) ? "X" : "")."</td>
                </tr>
            ";
        }
        echo "
            <tr>
                <td>TOTAL</td>
                <td><bold>".$totalOrders["entree"]."</bold></td>
                <td><bold>".$totalOrders["plat-1"]."</bold></td>
                <td><bold>".$totalOrders["plat-2"]."</bold></td>
                <td><bold>".$totalOrders["dessert-1"]."</bold></td>
                <td><bold>".$totalOrders["dessert-2"]."</bold></td>
            </tr>
        "
        ?>
        </tbody>
    </table>
  
    </div>
</body>
</html>
