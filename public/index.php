<?php
/**
 * Application Entry Point
 * 
 * This is the main entry point for the RideRentPro application.
 * It initializes the session, defines the base path, loads core MVC files,
 * and starts the routing system to handle incoming requests.
 * 
 * @package RideRentPro
 * @author RideRent Pro Team
 * @version 1.0.0
 */

session_start();

// Define project base paths so the app works correctly under XAMPP.
define('BASE_PATH', dirname(__DIR__));
define('APP_BASE_URL', '/RideRentPro/public');

// Load core files
require_once BASE_PATH . '/app/core/Database.php';
require_once BASE_PATH . '/app/core/Model.php';
require_once BASE_PATH . '/app/core/Controller.php';
require_once BASE_PATH . '/app/core/View.php';
require_once BASE_PATH . '/app/core/Router.php';

// Initialize router
$router = new Router();
?>