<?php
/**
 * Base Controller class for handling HTTP requests and responses
 * 
 * @package RideRentPro\Core
 * @author RideRent Pro Team
 * @version 1.0.0
 */
class Controller {
    /**
     * View instance
     * @var View
     */
    protected $view;
    
    /**
     * Model instance
     * @var Model
     */
    protected $model;
    
    /**
     * Data to pass to views
     * @var array
     */
    protected $data = [];
    
    /**
     * Constructor - Initialize view
     */
    public function __construct() {
        $this->view = new View();
    }
    
    /**
     * Load model by name
     * 
     * @param string $name Model name
     * @return void
     */
    public function loadModel($name) {
        $modelPath = __DIR__ . '/../models/' . $name . '.php';
        if (file_exists($modelPath)) {
            require_once $modelPath;
            $modelName = $name;
            $this->model = new $modelName();
        }
    }
    
    /**
     * Redirect to specified URL
     * 
     * @param string $url Target URL
     * @return void
     */
    public function redirect($url) {
        $target = $url;

        if (!preg_match('#^https?://#', $url) && !preg_match('#^/#', $url)) {
            $target = '/' . ltrim($url, '/');
        }

        if (strpos($target, APP_BASE_URL) === false && strpos($target, 'http') !== 0) {
            $target = APP_BASE_URL . '/' . ltrim($target, '/');
        }

        header("Location: $target");
        exit();
    }
    
    /**
     * Check if user is logged in
     * 
     * @return bool True if logged in
     */
    public function isLoggedIn() {
        return isset($_SESSION['user_id']) || isset($_SESSION['admin_id']) || 
               isset($_SESSION['owner_id']) || isset($_SESSION['driver_id']) || 
               isset($_SESSION['customer_id']);
    }
    
    /**
     * Get current user role
     * 
     * @return string|null User role or null if not logged in
     */
    public function getUserRole() {
        if (isset($_SESSION['admin']) || isset($_SESSION['admin_id'])) return 'admin';
        if (isset($_SESSION['owner_id'])) return 'owner';
        if (isset($_SESSION['driver_id'])) return 'driver';
        if (isset($_SESSION['customer_id'])) return 'customer';
        return null;
    }
    
    /**
     * Require user login with optional role check
     * 
     * @param string|null $role Required role
     * @return void
     */
    public function requireLogin($role = null) {
        if (!$this->isLoggedIn()) {
            $this->redirect('/auth/login');
        }
        
        if ($role && $this->getUserRole() !== $role) {
            $this->redirect('/unauthorized');
        }
    }
    
    /**
     * Sanitize input data
     * 
     * @param string|array $data Data to sanitize
     * @return string|array Sanitized data
     */
    public function sanitize($data) {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        return htmlspecialchars(strip_tags(trim($data)));
    }
    
    /**
     * Set flash message for next request
     * 
     * @param string $type Message type (success, error, warning, info)
     * @param string $message Message content
     * @return void
     */
    public function setFlash($type, $message) {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }
    
    /**
     * Get and clear flash message
     * 
     * @return array|null Flash message or null
     */
    public function getFlash() {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }
    
    /**
     * Render view template
     * 
     * @param string $view View path relative to views directory
     * @param array $data Data to pass to view
     * @return void
     */
    public function render($view, $data = []) {
        $this->view->render($view, $data);
    }
    
    /**
     * Render view with layout
     * 
     * @param string $view View path relative to views directory
     * @param array $data Data to pass to view
     * @return void
     */
    public function renderWithLayout($view, $data = []) {
        $this->data = array_merge($this->data, $data);
        extract($this->data);
        
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            ob_start();
            require_once $viewFile;
            $content = ob_get_clean();
            
            // Extract pageTitle if set
            $pageTitle = isset($data['pageTitle']) ? $data['pageTitle'] : 'RideRent Pro';
            
            // Include layout
            require_once __DIR__ . '/../views/layouts/main.php';
        } else {
            die("View file not found: $viewFile");
        }
    }
}
?>