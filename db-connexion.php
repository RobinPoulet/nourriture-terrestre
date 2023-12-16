<?php 
// Chemin du fichier .env
$envFilePath = __DIR__ . '/.env';

// Vérifiez si le fichier .env existe
if (file_exists($envFilePath)) {
    $envContents = file_get_contents($envFilePath);
    $envLines = explode("\n", $envContents);

    foreach ($envLines as $line) {
        $line = trim($line);

        // Ignore les lignes vides ou celles commençant par un dièse (#)
        if (empty($line) || strpos($line, '#') === 0) {
            continue;
        }

        // Diviser chaque ligne en variable et en valeur
        list($key, $value) = explode('=', $line, 2);

        // Nettoyez les espaces autour des clés et des valeurs
        $key = trim($key);
        $value = trim($value);

        // Définir les variables d'environnement dans $_ENV
        $_ENV[$key] = $value;
    }

    // Définition des variables d'environnements pour la connexion à la db
    $dbHost = $_ENV['DB_HOST'];
    $dbUser = $_ENV['DB_USER'];
    $dbPass = $_ENV['DB_PASS'];
    $dbName = $_ENV['DB_NAME'];
} else {
    die("Le fichier .env n'a pas été trouvé.");
}
// Connexion à la base de données avec pdo
try {
    $pdo = new PDO('mysql:host=' . $dbHost . ';dbname=' . $dbName, $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}
