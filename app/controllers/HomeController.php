<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Vehicle.php';

/**
 * Home Controller for handling home page requests
 * 
 * @package RideRentPro\Controllers
 * @author RideRent Pro Team
 * @version 1.0.0
 */
class HomeController extends Controller {
    /**
     * Vehicle model instance
     * @var Vehicle
     */
    private $vehicleModel;
    
    /**
     * Constructor - Initialize vehicle model
     */
    public function __construct() {
        parent::__construct();
        $this->vehicleModel = new Vehicle();
    }
    
    /**
     * Render home page with vehicles and user info
     * 
     * @return void
     */
    public function index() {
        $vehicles = $this->vehicleModel->getAll();
        
        // Handle case where query fails
        if ($vehicles === false) {
            $vehicles = [];
        }
        
        $user = null;
        if ($this->isLoggedIn()) {
            $role = $this->getUserRole();
            $name = $this->getUserName();
            $user = [
                'logged_in' => true,
                'role' => $role,
                'name' => $name,
                'dashboard_url' => "/$role/dashboard"
            ];
        }
        
        $this->render('home/index', [
            'vehicles' => $vehicles,
            'user' => $user
        ]);
    }
    
    /**
     * Get current user name from session
     * 
     * @return string|null User name or null if not logged in
     */
    private function getUserName() {
        if (isset($_SESSION['admin'])) return 'Admin';
        if (isset($_SESSION['owner_name'])) return $_SESSION['owner_name'];
        if (isset($_SESSION['driver_name'])) return $_SESSION['driver_name'];
        if (isset($_SESSION['customer_name'])) return $_SESSION['customer_name'];
        return null;
    }
}
?>