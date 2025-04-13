<?php

namespace App\Core;

use App\Http\Request;
use Exception;

require_once './config/constants.php';
class Router
{
    private string $prefix;
    private const string GET = 'GET';
    private const string POST = 'POST';
    private array $routes = [
        '/index' => [
            'method' => self::GET,
            'controller' => 'HomeController',
            'action' => 'index'
        ],
        '/' => [
            'method' => self::GET,
            'controller' => 'HomeController',
            'action' => 'index'
        ],
        '/commande' => [
            'method' => self::GET,
            'controller' => 'OrderController',
            'action' => 'create'
        ],
        '/users' => [
            'method' => self::GET,
            'controller' => 'UserController',
            'action' => 'index'
        ],
        '/display-orders' => [
            'method' => self::GET,
            'controller' => 'OrderController',
            'action' => 'index'
        ],
        '/create-user' => [
            'method' => self::POST,
            'controller' => 'UserController',
            'action' => 'create'
        ],
        '/create-order' => [
            'method' => self::POST,
            'controller' => 'OrderController',
            'action' => 'store'
        ],
        '/edit-order/:orderId' => [
            'method' => self::POST,
            'pattern' => '#^/edit-order/(\d+)#',
            'params' => ['orderId'],
            'controller' => 'OrderController',
            'action' => 'edit'
        ],
        '/delete-order/:orderId' => [
            'method' => self::GET,
            'pattern' => '#^/delete-order/(\d+)#',
            'params' => ['orderId'],
            'controller' => 'OrderController',
            'action' => 'delete'
        ],
        '/edit-user/:userId' => [
            'method' => self::POST,
            'pattern' => '#^/edit-user/(\d+)#',
            'params' => ['userId'],
            'controller' => 'UserController',
            'action' => 'edit'
        ],
        '/delete-user/:userId' => [
            'method' => self::GET,
            'pattern' => '#^/delete-user/(\d+)#',
            'params' => ['userId'],
            'controller' => 'UserController',
            'action' => 'delete'
        ],
        '/get-all-users' => [
            'method' => self::POST,
            'controller' => 'UserController',
            'action' => 'getAllUsers'
        ]
    ];

    /**
     * @throws Exception
     */
    public function handleRequest()
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestUri = str_replace(PREFIX, '', $requestUri);
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        // Vérifier les routes statiques
        if (isset($this->routes[$requestUri]) && $this->routes[$requestUri]['method'] === $requestMethod) {
            $this->dispatch($requestUri, $requestMethod);
            return;
        }
        // Vérifier les routes dynamiques
        foreach ($this->routes as $route => $config) {
            if (isset($config['pattern']) && preg_match($config['pattern'], $requestUri, $matches)) {
                $params = [];
                // Récupérer les paramètres dynamiques en utilisant le tableau `params`
                foreach ($config['params'] as $index => $paramName) {
                    $params[$paramName] = $matches[$index + 1];  // +1 car le premier match est le texte complet
                }
                // Appeler la méthode du contrôleur avec les paramètres
                $this->dispatch($route, $requestMethod, $params);
                return;
            }
        }

        // Si aucune route ne correspond, afficher une 404
        http_response_code(404);
        if ($this->isAjax()) {
            echo json_encode(['error' => 'Page non trouvée']);
        } else {
            echo "404 - Page non trouvée";
        }
    }

    /**
     * Appelle la méthode du contrôleur en lui passant les paramètres.
     */
    private function dispatch(string $route, string $requestMethod, array $params = [])
    {
        $controllerName = "App\\Controllers\\" . $this->routes[$route]['controller'];
        $methodName = $this->routes[$route]['action'];

        if (class_exists($controllerName) && method_exists($controllerName, $methodName)) {
            $controller = new $controllerName();

            // Si la requête est de type POST, on injecte un objet Request
            if ($requestMethod === self::POST) {
                $request = new Request($_GET, $_POST, $_FILES);
                $response = $controller->$methodName($request, ...$params);
            } else {
                $response = $controller->$methodName(...$params);
            }

            echo $response;
        }
    }

    /**
     * Vérifie si la requête est une requête AJAX.
     */
    private function isAjax(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * @throws Exception
     */
    private function isMenuOpen($postData): bool
    {

        return (
            isset($postData['menu']['success'])
            && $postData['menu']['success']['menu']['IS_OPEN'] === '1'
        );
    }
}
