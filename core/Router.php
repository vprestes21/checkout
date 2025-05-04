<?php
class Router {
    private $controller = 'home';
    private $method = 'index';
    private $params = [];
    
    public function dispatch() {
        $url = $this->parseUrl();
        
        $controllerName = !empty($url[0]) ? ucfirst($url[0]) . 'Controller' : 'HomeController';
        $methodName = !empty($url[1]) ? $url[1] : 'index';
        
        array_shift($url); // Remove controller
        if (!empty($url)) {
            array_shift($url); // Remove method if present
        }
        
        $controllerFile = 'controllers/' . $controllerName . '.php';
        
        // Include base Controller class first
        require_once 'core/Controller.php';
        
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            
            if (class_exists($controllerName)) {
                $controller = new $controllerName();
                
                // Special handling for checkout routes
                if ($controllerName === 'CheckoutController') {
                    // If methodName looks like a slug (no method exists), handle it differently
                    if (!empty($methodName) && !method_exists($controller, $methodName)) {
                        // Call the show method with the slug
                        call_user_func([$controller, 'show'], $methodName);
                        return;
                    }
                }
                
                if (method_exists($controller, $methodName)) {
                    call_user_func_array([$controller, $methodName], $url);
                } else {
                    // Try magic __call method first
                    if (method_exists($controller, '__call')) {
                        call_user_func_array([$controller, $methodName], $url);
                    } else {
                        // Method not found - 404
                        $this->handleNotFound("Method {$methodName} not found");
                    }
                }
            } else {
                // Controller class not found - 404
                $this->handleNotFound("Controller {$controllerName} not found");
            }
        } else {
            // Controller file not found - 404
            $this->handleNotFound("Controller file {$controllerFile} not found");
        }
    }
    
    protected function parseUrl() {
        if(isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
    
    /**
     * Handle 404 Not Found errors
     * 
     * @param string $message Error message
     * @return void
     */
    protected function handleNotFound($message = 'Page not found') {
        http_response_code(404);
        
        // Check if there's a custom 404 view
        if (file_exists('views/errors/404.php')) {
            require_once 'views/errors/404.php';
        } else {
            // Simple fallback error display
            echo '<!DOCTYPE html>
                <html>
                <head>
                    <title>404 Not Found</title>
                    <style>
                        body { font-family: Arial, sans-serif; text-align: center; padding-top: 100px; }
                        h1 { color: #e74c3c; }
                    </style>
                </head>
                <body>
                    <h1>404 Not Found</h1>
                    <p>' . htmlspecialchars($message) . '</p>
                    <p><a href="' . BASE_URL . '">Return to homepage</a></p>
                </body>
                </html>';
        }
        
        exit;
    }
}

// Antes de adicionar rotas, certifique-se de instanciar o objeto Router:
if (!isset($router) || !$router) {
    $router = new Router();
}

// Agora pode adicionar as rotas:
// Substitua $router->register(...) por o método correto da sua classe Router.
// Se sua classe Router não tem add() nem register(), normalmente o método é route(), map(), ou você deve adicionar a rota no array de rotas manualmente.

// Exemplo genérico para array de rotas:
$routes['checkout/status/([0-9]+)'] = ['controller' => 'CheckoutController', 'action' => 'status'];

// Se sua classe Router tem um método chamado route():
// $router->route('checkout/status/([0-9]+)', ['controller' => 'CheckoutController', 'action' => 'status']);

// Se for um array, certifique-se que o array de rotas está sendo usado pelo seu roteador.
