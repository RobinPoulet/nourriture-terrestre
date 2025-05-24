<?php

namespace App\Core;

class Http
{
    /**
     * Redirige vers une route définie dans le routeur.
     *
     * @param string $route Nom de la route définie dans le Router.
     * @param array $params Paramètres optionnels à inclure dans l'URL.
     */
    public static function redirect(string $route, array $params = []): void
    {
        // Construire l'URL de redirection
        $url = COMPLETE_URL."/".$route;

        // Ajouter les paramètres GET s'il y en a
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        // Redirection
        header("Location: " . $url);
        exit();
    }
}