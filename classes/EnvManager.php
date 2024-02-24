<?php


class EnvManager {
    private static $instance;
    private $env;

    private function __construct($envFilePath) {
        // Vérifier si le fichier .env existe
        if (file_exists($envFilePath)) {
            $envContents = file_get_contents($envFilePath);
            $envLines = explode("\n", $envContents);

            foreach ($envLines as $line) {
                $line = trim($line);

                // Ignorer les lignes vides ou celles commençant par un dièse (#)
                if (empty($line) || strpos($line, '#') === 0) {
                    continue;
                }

                // Diviser chaque ligne en variable et en valeur
                list($key, $value) = explode('=', $line, 2);

                // Nettoyer les espaces autour des clés et des valeurs
                $key = trim($key);
                $value = trim($value);

                // Définir les variables d'environnement dans $this->env
                $this->env[$key] = $value;
            }
        } else {
            die("Le fichier .env n'a pas été trouvé.");
        }
    }

    public static function getInstance($envFilePath) {
        if (!self::$instance) {
            self::$instance = new self($envFilePath);
        }
        return self::$instance;
    }

    public function getEnvVariable($key) {
        return $this->env[$key] ?? null;
    }
}