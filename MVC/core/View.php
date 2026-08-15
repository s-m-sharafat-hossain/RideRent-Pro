<?php
/**
 * View class for rendering templates
 * 
 * @package RideRentPro\Core
 * @author RideRent Pro Team
 * @version 1.0.0
 */
class View {
    /**
     * Path to views directory
     * @var string
     */
    private $viewPath;
    
    /**
     * Data to pass to views
     * @var array
     */
    private $data = [];
    
    /**
     * Constructor - Set view path
     */
    public function __construct() {
        $this->viewPath = __DIR__ . '/../views/';
    }
    
    /**
     * Render view template
     * 
     * @param string $view View path relative to views directory
     * @param array $data Data to pass to view
     * @return void
     */
    public function render($view, $data = []) {
        $this->data = array_merge($this->data, $data);
        extract($this->data);
        
        $viewFile = $this->viewPath . $view . '.php';
        
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            die("View file not found: $viewFile");
        }
    }
    
    /**
     * Render view with layout
     * 
     * @param string $layout Layout name
     * @param string $content Content view name
     * @param array $data Data to pass to view
     * @return void
     */
    public function renderLayout($layout, $content, $data = []) {
        $this->data = array_merge($this->data, $data);
        extract($this->data);
        
        $layoutFile = $this->viewPath . 'layouts/' . $layout . '.php';
        $contentFile = $this->viewPath . $content . '.php';
        
        if (file_exists($layoutFile) && file_exists($contentFile)) {
            ob_start();
            require_once $contentFile;
            $content = ob_get_clean();
            
            require_once $layoutFile;
        } else {
            die("Layout or content file not found");
        }
    }
    
    /**
     * Set view data variable
     * 
     * @param string $key Variable name
     * @param mixed $value Variable value
     * @return void
     */
    public function set($key, $value) {
        $this->data[$key] = $value;
    }
    
    /**
     * Get view data variable
     * 
     * @param string $key Variable name
     * @param mixed $default Default value if not set
     * @return mixed Variable value or default
     */
    public function get($key, $default = null) {
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }
    
    /**
     * Escape string for HTML output
     * 
     * @param string $string String to escape
     * @return string Escaped string
     */
    public function escape($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Generate URL path
     * 
     * @param string $path Path relative to public directory
     * @return string Full URL
     */
    public function url($path) {
        return APP_BASE_URL . '/' . ltrim($path, '/');
    }
    
    /**
     * Generate asset path
     * 
     * @param string $path Asset path relative to assets directory
     * @return string Full asset URL
     */
    public function asset($path) {
        return APP_BASE_URL . '/assets/' . ltrim($path, '/');
    }
}
?>