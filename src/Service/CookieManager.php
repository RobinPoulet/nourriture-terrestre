<?php

namespace App\Service;

class CookieManager
{
    /** @var int Durée de vie d'un cookie (2 ans ici) */
    private const int COOKIE_LIFETIME = 60 * 60 * 24 * 730;

    /** @var int Durée restante pour le raffraichissement du cookie (1 mois ici) */
    private const int RENEW_THRESHOLD = 60 * 60 * 24 * 30;

    /**
     * Récupère un cookie par son nom
     *
     * @param string $name Nom du cookie
     *
     * @return ?object
     */
    public function get(string $name): ?array
    {
        $returnValue = null;

        if (isset($_COOKIE[$name])) {
            $decoded = base64_decode($_COOKIE[$name], true);
            $data = json_decode($decoded, true);
            $returnValue = ($data ?: null);
        }

        return $returnValue;
    }

    /**
     * Définir un cookie
     *
     * @param string $name Nom du cookie
     * @param array $data Données à stocker dans le cookie
     *
     * @return void
     */
    public function set(string $name, array $data): void
    {
        $expiry = time() + self::COOKIE_LIFETIME;
        $data["expiry"] = $expiry;

        $payload = base64_encode(json_encode($data));
        setcookie($name, $payload, $expiry, "/");
    }

    /**
     * Vérifie si un cookie a besoin d'être raffraichi
     *
     * @param string $name Nom du cookie
     * @param array $data Données à stocker dans le cookie (si besoin de le créer)
     * @param bool $isResetCookie Le cookie doit être reset ?
     *
     * @return void
     */
    public function refreshIfNeeded(string $name, array $data, bool $isResetCookie): void
    {
        $existingData = $this->get($name);

        if (
            isset($existingData)
            && !$isResetCookie
            && is_array($existingData)
        ) {
            $now = time();
            if ($data["expiry"] - $now < self::RENEW_THRESHOLD) {
                $this->set($name, $existingData);
            }
        } else {
            $this->set($name, $data);
        }


    }
}