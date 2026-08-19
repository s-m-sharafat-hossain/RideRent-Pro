<?php
/**
 * Bootstrap file for PHPUnit tests
 * Sets up autoloading and test environment
 */

// Define base path
define('BASE_PATH', __DIR__ . '/../');
define('APP_PATH', BASE_PATH . 'app/');

// Autoloader for app classes
spl_autoload_register(function($class) {
    // Map class to file path
    $classPath = str_replace('\\', '/', $class);
    $filePath = APP_PATH . $classPath . '.php';
    
    if (file_exists($filePath)) {
        require_once $filePath;
        return true;
    }
    return false;
});

// Load all required core classes
require_once APP_PATH . 'core/Database.php';
require_once APP_PATH . 'core/Model.php';
require_once APP_PATH . 'core/Controller.php';
require_once APP_PATH . 'core/View.php';

// Load all models
require_once APP_PATH . 'models/User.php';
require_once APP_PATH . 'models/Booking.php';
require_once APP_PATH . 'models/Vehicle.php';
require_once APP_PATH . 'models/Review.php';
require_once APP_PATH . 'models/Driver.php';

// Load all controllers
require_once APP_PATH . 'controllers/CustomerController.php';
require_once APP_PATH . 'controllers/AuthController.php';

// Autoload test helpers and base classes
require_once __DIR__ . '/TestCase.php';

// Set up test environment variables
$_ENV['APP_ENV'] = 'testing';

// Start session for tests
if (!isset($_SESSION)) {
    session_start();
}
