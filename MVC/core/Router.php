<?php
/**
 * Router class for URL routing and dispatching
 * 
 * @package RideRentPro\Core
 * @author RideRent Pro Team
 * @version 1.0.0
 */
class Router {
    /**
     * Controller name
     * @var string
     */
    private $controller;
    
    /**
     * Method name
     * @var string
     */
    private $method;
    
    /**
     * URL parameters
     * @var array
     */
    private $params = [];
    
    /**
     * Constructor - Parse URL and setup routing
     */
    public function __construct() {
        $url = $this->parseUrl();
        
        // Handle root route
        if (empty($url) || $url[0] === '') {
            $this->controller = 'HomeController';
            $this->method = 'index';
        } else {
            $this->controller = ucfirst($url[0]) . 'Controller';
            array_shift($url);
            
            $this->method = isset($url[0]) ? $url[0] : 'index';
            array_shift($url);
            
            $this->params = $url;
        }
        
        $this->dispatch();
    }
    
    /**
     * Parse URL from GET parameter
     * 
     * @return array URL segments
     */
    private function parseUrl() {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            // Replace hyphens with underscores for method names
            $url = str_replace('-', '_', $url);
            return explode('/', $url);
        }
        return [];
    }
    
    /**
     * Dispatch request to appropriate controller and method
     * 
     * @return void
     */
    private function dispatch() {
        $controllerFile = __DIR__ . '/../controllers/' . $this->controller . '.php';
        
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            
            if (class_exists($this->controller)) {
                $this->controller = new $this->controller();
                
                if (method_exists($this->controller, $this->method)) {
                    call_user_func_array([$this->controller, $this->method], $this->params);
                } else {
                    $this->error('Method not found');
                }
            } else {
                $this->error('Controller class not found');
            }
        } else {
            $this->error('Controller file not found');
        }
    }
    
    /**
     * Display error page
     * 
     * @param string $message Error message
     * @return void
     */
    private function error($message) {
        http_response_code(404);
        echo "<h1>404 - Page Not Found</h1>";
        echo "<p>$message</p>";
        exit();
    }
}
?>