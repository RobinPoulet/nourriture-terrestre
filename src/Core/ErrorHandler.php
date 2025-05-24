<?php

namespace App\Core;

use Throwable;

class ErrorHandler
{
    protected bool $debug;

    public function __construct(bool $debug = false)
    {
        $this->debug = $debug;
    }

    /**
     * @param Throwable $e
     * @return void
     */
    public function handle(Throwable $e): void
    {
        error_log($e->getMessage() . "\n" . $e->getTraceAsString(), 3, BASE_PATH . '/logs/error.log');
        http_response_code(500);
        if ($this->debug) {
            echo '<h1>Une erreur est survenue :</h1>';
            echo '<p><strong>' . get_class($e) . ':</strong> ' . $e->getMessage() . '</p>';
            echo '<pre>' . $e->getTraceAsString() . '</pre>';
        } else {
            // Page d'erreur personnalisée
            require_once BASE_PATH . '/views/errors/500.php';
        }
    }
}
