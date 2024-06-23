<?php


class EnvManager {
    /**
     * @var EnvManager Instance de la classe EnvManager
     */
    private static $instance;
    /**
     * @var array Tableau contenant les variables d'environnements
     */
    private $env;

    /**
     * Constructeur privé de la classe
     *
     * @param string $envFilePath
     */
    private function __construct(string $envFilePath)
    {
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
                [$key, $value] = explode('=', $line, 2);

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

    /**
     * retourner une instance de EnvManager
     *
     * @param string $envFilePath
     *
     * @return EnvManager
     */
    public static function getInstance(string $envFilePath): EnvManager
    {
        if (!self::$instance) {
            self::$instance = new self($envFilePath);
        }
        return self::$instance;
    }

    /**
     * Retourne la valeur d'une variable d'environnement selon sa clé
     *
     * @param string $key
     *
     * @return null|string
     */
    public function getEnvVariable(string $key): ?string
    {
        return $this->env[$key] ?? null;
    }
    
    /**
     * Retourne toutes les variables d'environnement sous forme de tableaux clé, valeur
     *
     * @return array
     */
    public function getAllEnvVariables(): array
    {
        return $this->env;
    }
}