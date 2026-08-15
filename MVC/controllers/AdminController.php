<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Vehicle.php';
require_once __DIR__ . '/../models/Booking.php';
require_once __DIR__ . '/../models/Driver.php';
require_once __DIR__ . '/../models/Review.php';

/**
 * Admin Controller for handling admin dashboard requests
 * 
 * @package RideRentPro\Controllers
 * @author RideRent Pro Team
 * @version 1.0.0
 */
class AdminController extends Controller {
    /**
     * User model instance
     * @var User
     */
    private $userModel;
    
    /**
     * Vehicle model instance
     * @var Vehicle
     */
    private $vehicleModel;
    
    /**
     * Booking model instance
     * @var Booking
     */
    private $bookingModel;
    
    /**
     * Driver model instance
     * @var Driver
     */
    private $driverModel;
    
    /**
     * Review model instance
     * @var Review
     */
    private $reviewModel;
    
    /**
     * Database instance
     * @var Database
     */
    private $db;
    
    /**
     * Constructor - Initialize all models and database
     */
    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
        $this->vehicleModel = new Vehicle();
        $this->bookingModel = new Booking();
        $this->driverModel = new Driver();
        $this->reviewModel = new Review();
        $this->db = Database::getInstance();
    }
    
    /**
     * Display admin dashboard with statistics
     *
     * @return void
     */
    
    public function dashboard() {
        $this->requireLogin('admin');
        
        $customers = $this->userModel->getAllByRole('customer');
        $owners = $this->userModel->getAllByRole('owner');
        $drivers = $this->driverModel->getAll();
        
        $stats = [
            'totalUsers' => count($customers) + count($owners) + count($drivers),
            'totalVehicles' => count($this->vehicleModel->getAll()),
            'totalDrivers' => count($drivers),
            'totalBookings' => count($this->bookingModel->getAll()),
            'recentBookings' => $this->bookingModel->getRecent(5),
            'reviewStats' => $this->reviewModel->getStats()
        ];
        
        $this->render('admin/dashboard', [
            'stats' => $stats,
            'userName' => 'Admin',
            'userRole' => 'Admin'
        ]);
    }
    
    /**
     * Display and manage users (list, delete, update status)
     *
     * @return void
     */
    public function users() {
        $this->requireLogin('admin');
        
        // Handle delete
        if (isset($_GET['delete']) && isset($_GET['type'])) {
            $id = $this->sanitize($_GET['delete']);
            $type = $this->sanitize($_GET['type']);
            
            $table = '';
            $id_field = '';
            
            switch($type) {
                case 'admin':
                    $table = 'admin';
                    $id_field = 'admin_id';
                    break;
                case 'customer':
                    $table = 'customer';
                    $id_field = 'customer_id';
                    break;
                case 'driver':
                    $table = 'driver';
                    $id_field = 'driver_id';
                    break;
                case 'owner':
                    $table = 'vehicle_owner';
                    $id_field = 'owner_id';
                    break;
            }
            
            if ($table && $id_field) {
                $sql = "DELETE FROM $table WHERE $id_field = '$id'";
                $this->db->query($sql);
            }
            
            $this->redirect('/admin/users');
        }
        
        // Handle status update
        if (isset($_GET['status']) && isset($_GET['type']) && isset($_GET['id'])) {
            $id = $this->sanitize($_GET['id']);
            $type = $this->sanitize($_GET['type']);
            $status = $this->sanitize($_GET['status']);
            
            $table = '';
            $id_field = '';
            
            switch($type) {
                case 'admin':
                    $table = 'admin';
                    $id_field = 'admin_id';
                    break;
                case 'customer':
                    $table = 'customer';
                    $id_field = 'customer_id';
                    break;
                case 'driver':
                    $table = 'driver';
                    $id_field = 'driver_id';
                    break;
                case 'owner':
                    $table = 'vehicle_owner';
                    $id_field = 'owner_id';
                    break;
            }
            
            if ($table && $id_field) {
                $sql = "UPDATE $table SET status = '$status' WHERE $id_field = '$id'";
                $this->db->query($sql);
            }
            
            $this->redirect('/admin/users');
        }
        
        $filter = isset($_GET['filter']) ? $this->sanitize($_GET['filter']) : 'all';
        
        $users = [
            'admins' => $this->userModel->getAllByRole('admin'),
            'customers' => $this->userModel->getAllByRole('customer'),
            'owners' => $this->userModel->getAllByRole('owner'),
            'drivers' => $this->driverModel->getAll()
        ];
        
        $this->render('admin/users', [
            'users' => $users,
            'filter' => $filter,
            'userName' => 'Admin'
        ]);
    }
    
    /**
     * List all vehicles for admin
     *
     * @return void
     */
    public function vehicles() {
        $this->requireLogin('admin');
        
        $vehicles = $this->vehicleModel->getAll();
        
        $this->render('admin/vehicles', [
            'vehicles' => $vehicles,
            'userName' => 'Admin'
        ]);
    }
    
    /**
     * List and manage bookings (status, payment, delete)
     *
     * @return void
     */
    public function bookings() {
        $this->requireLogin('admin');
        
        // Handle status update
        if (isset($_GET['status']) && isset($_GET['id'])) {
            $id = $this->sanitize($_GET['id']);
            $status = $this->sanitize($_GET['status']);
            $this->bookingModel->updateStatus($id, $status);
            $this->redirect('/admin/bookings');
        }
        
        // Handle payment status update
        if (isset($_GET['payment_status']) && isset($_GET['id'])) {
            $id = $this->sanitize($_GET['id']);
            $payment_status = $this->sanitize($_GET['payment_status']);
            $this->bookingModel->updatePaymentStatus($id, $payment_status);
            $this->redirect('/admin/bookings');
        }
        
        // Handle delete
        if (isset($_GET['delete'])) {
            $id = $this->sanitize($_GET['delete']);
            $sql = "DELETE FROM booking WHERE booking_id = '$id'";
            $this->db->query($sql);
            $this->redirect('/admin/bookings');
        }
        
        $bookings = $this->bookingModel->getAll();
        
        $this->render('admin/bookings', [
            'bookings' => $bookings,
            'userName' => 'Admin'
        ]);
    }
    
    /**
     * Manage drivers (list, delete, availability, status)
     *
     * @return void
     */
    public function drivers() {
        $this->requireLogin('admin');
        
        // Handle delete
        if (isset($_GET['delete'])) {
            $id = $this->sanitize($_GET['delete']);
            $sql = "DELETE FROM driver WHERE driver_id = '$id'";
            $this->db->query($sql);
            $this->redirect('/admin/drivers');
        }
        
        // Handle status update
        if (isset($_GET['status']) && isset($_GET['id'])) {
            $id = $this->sanitize($_GET['id']);
            $status = $this->sanitize($_GET['status']);
            $sql = "UPDATE driver SET status = '$status' WHERE driver_id = '$id'";
            $this->db->query($sql);
            $this->redirect('/admin/drivers');
        }
        
        // Handle availability update
        if (isset($_GET['availability']) && isset($_GET['id'])) {
            $id = $this->sanitize($_GET['id']);
            $availability = $this->sanitize($_GET['availability']);
            $this->driverModel->updateAvailability($id, $availability);
            $this->redirect('/admin/drivers');
        }
        
        $drivers = $this->driverModel->getAll();
        
        $this->render('admin/drivers', [
            'drivers' => $drivers,
            'userName' => 'Admin'
        ]);
    }
    
    /**
     * Approve, reject or delete reviews and update related ratings
     *
     * @return void
     */
    public function reviews() {
        $this->requireLogin('admin');
        
        // Handle approval/rejection
        if (isset($_GET['action']) && isset($_GET['id'])) {
            $id = $this->sanitize($_GET['id']);
            $action = $this->sanitize($_GET['action']);
            $status = ($action == 'approve') ? 'approved' : 'rejected';
            
            // Get review details before updating
            $review = $this->reviewModel->getById($id);
            
            $sql = "UPDATE reviews SET status = '$status' WHERE review_id = '$id'";
            $this->db->query($sql);
            
            // Update ratings if approving a review
            if ($action == 'approve' && $review) {
                if ($review['target_type'] == 'driver') {
                    $this->updateDriverRating($review['target_id']);
                }
                if ($review['target_type'] == 'vehicle') {
                    $this->updateVehicleRating($review['target_id']);
                }
            }
            
            $this->redirect('/admin/reviews');
        }
        
        // Handle delete
        if (isset($_GET['delete'])) {
            $id = $this->sanitize($_GET['delete']);
            
            // Get review details before deleting
            $review = $this->reviewModel->getById($id);
            
            $sql = "DELETE FROM reviews WHERE review_id = '$id'";
            $this->db->query($sql);
            
            // Update ratings after deletion
            if ($review) {
                if ($review['target_type'] == 'driver') {
                    $this->updateDriverRating($review['target_id']);
                }
                if ($review['target_type'] == 'vehicle') {
                    $this->updateVehicleRating($review['target_id']);
                }
            }
            
            $this->redirect('/admin/reviews');
        }
        
        $reviews = $this->reviewModel->getAll();
        
        $this->render('admin/reviews', [
            'reviews' => $reviews,
            'userName' => 'Admin'
        ]);
    }
    
    /**
     * Recalculate and update average rating and count for a driver
     *
     * @param int $driver_id Driver identifier
     * @return void
     */
    private function updateDriverRating($driver_id) {
        $reviews = $this->db->query("SELECT rating FROM reviews WHERE target_type = 'driver' AND target_id = '$driver_id'");
        
        if ($reviews && $this->db->numRows($reviews) > 0) {
            $total = 0;
            $count = 0;
            while ($row = $this->db->fetchAssoc($reviews)) {
                $total += $row['rating'];
                $count++;
            }
            $avg_rating = $total / $count;
            
            $this->db->query("UPDATE driver SET rating = '$avg_rating', rating_count = '$count' WHERE driver_id = '$driver_id'");
        } else {
            $this->db->query("UPDATE driver SET rating = 0.00, rating_count = 0 WHERE driver_id = '$driver_id'");
        }
    }
    
    /**
     * Recalculate and update average rating and count for a vehicle
     *
     * @param int $vehicle_id Vehicle identifier
     * @return void
     */
    private function updateVehicleRating($vehicle_id) {
        $reviews = $this->db->query("SELECT rating FROM reviews WHERE target_type = 'vehicle' AND target_id = '$vehicle_id'");
        
        if ($reviews && $this->db->numRows($reviews) > 0) {
            $total = 0;
            $count = 0;
            while ($row = $this->db->fetchAssoc($reviews)) {
                $total += $row['rating'];
                $count++;
            }
            $avg_rating = $total / $count;
            
            // Add rating column to vehicle table if it doesn't exist
            $check_column = $this->db->query("SHOW COLUMNS FROM vehicle LIKE 'rating'");
            if ($this->db->numRows($check_column) == 0) {
                $this->db->query("ALTER TABLE vehicle ADD COLUMN rating DECIMAL(3,2) DEFAULT 0.00");
                $this->db->query("ALTER TABLE vehicle ADD COLUMN rating_count INT DEFAULT 0");
            }
            
            $this->db->query("UPDATE vehicle SET rating = '$avg_rating', rating_count = '$count' WHERE vehicle_id = '$vehicle_id'");
        } else {
            $check_column = $this->db->query("SHOW COLUMNS FROM vehicle LIKE 'rating'");
            if ($this->db->numRows($check_column) > 0) {
                $this->db->query("UPDATE vehicle SET rating = 0.00, rating_count = 0 WHERE vehicle_id = '$vehicle_id'");
            }
        }
    }
    
    /**
     * Generate overview reports for vehicles and availability
     *
     * @return void
     */
    public function reports() {
        $this->requireLogin('admin');
        
        // Count Vehicles
        $vehicleQuery = $this->db->query("SELECT * FROM vehicle");
        $totalVehicles = $this->db->numRows($vehicleQuery);
        
        // Available Vehicles
        $availableQuery = $this->db->query("SELECT * FROM vehicle WHERE availability='Available'");
        $availableVehicles = $this->db->numRows($availableQuery);
        
        // Booked Vehicles
        $bookedQuery = $this->db->query("SELECT * FROM vehicle WHERE availability='Booked'");
        $bookedVehicles = $this->db->numRows($bookedQuery);
        
        // Maintenance
        $maintenanceQuery = $this->db->query("SELECT * FROM vehicle WHERE availability='Maintenance'");
        $maintenanceVehicles = $this->db->numRows($maintenanceQuery);
        
        // Latest Vehicles
        $latestVehicles = $this->db->query("SELECT * FROM vehicle ORDER BY vehicle_id DESC LIMIT 6");
        $vehicles = [];
        while ($row = $this->db->fetchAssoc($latestVehicles)) {
            $vehicles[] = $row;
        }
        
        $stats = [
            'totalVehicles' => $totalVehicles,
            'availableVehicles' => $availableVehicles,
            'bookedVehicles' => $bookedVehicles,
            'maintenanceVehicles' => $maintenanceVehicles,
            'latestVehicles' => $vehicles
        ];
        
        $this->render('admin/reports', [
            'stats' => $stats,
            'userName' => 'Admin'
        ]);
    }
    
    /**
     * Handle vehicle approval workflow (approve/reject/delete)
     *
     * @return void
     */
    public function vehicleApprovals() {
        $this->requireLogin('admin');
        
        // Handle approval/rejection/deletion
        if (isset($_GET['action']) && isset($_GET['id'])) {
            $vehicle_id = $this->sanitize($_GET['id']);
            $action = $this->sanitize($_GET['action']);
            
            if ($action == 'approve') {
                $update = "UPDATE vehicle SET approval_status = 'Approved' WHERE vehicle_id = '$vehicle_id'";
                $this->db->query($update);
                $this->setFlash('success', 'Vehicle approved successfully!');
                $this->redirect('/admin/vehicle-approvals');
            } elseif ($action == 'reject') {
                $update = "UPDATE vehicle SET approval_status = 'Rejected' WHERE vehicle_id = '$vehicle_id'";
                $this->db->query($update);
                $this->setFlash('success', 'Vehicle rejected successfully!');
                $this->redirect('/admin/vehicle-approvals');
            } elseif ($action == 'delete') {
                $delete = "DELETE FROM vehicle WHERE vehicle_id = '$vehicle_id'";
                if ($this->db->query($delete)) {
                    $this->setFlash('success', 'Vehicle deleted successfully!');
                } else {
                    $this->setFlash('error', 'Failed to delete vehicle');
                }
                $this->redirect('/admin/vehicle-approvals');
            }
        }
        
        // Pending vehicles
        $pendingSql = "SELECT v.*, o.full_name as owner_name FROM vehicle v 
                      LEFT JOIN vehicle_owner o ON v.owner_id = o.owner_id 
                      WHERE v.approval_status = 'Pending' 
                      ORDER BY v.created_at DESC";
        $pendingVehicles = $this->db->query($pendingSql);
        $pending = [];
        while ($row = $this->db->fetchAssoc($pendingVehicles)) {
            $pending[] = $row;
        }
        
        // All vehicles
        $allSql = "SELECT v.*, o.full_name as owner_name FROM vehicle v 
                   LEFT JOIN vehicle_owner o ON v.owner_id = o.owner_id 
                   ORDER BY v.created_at DESC";
        $allVehicles = $this->db->query($allSql);
        $all = [];
        while ($row = $this->db->fetchAssoc($allVehicles)) {
            $all[] = $row;
        }
        
        $this->render('admin/vehicle_approvals', [
            'pendingVehicles' => $pending,
            'allVehicles' => $all,
            'message' => $this->getFlash(),
            'userName' => 'Admin'
        ]);
    }
    
    /**
     * Assign or remove drivers for bookings and show assignment queues
     *
     * @return void
     */
    public function driverAssignment() {
        $this->requireLogin('admin');
        
        // Handle driver assignment
        if (isset($_POST['assign_driver'])) {
            $booking_id = intval($_POST['booking_id']);
            $driver_id = intval($_POST['driver_id']);
            
            // Get booking details
            $bookingQuery = $this->db->query("SELECT * FROM booking WHERE booking_id = '$booking_id'");
            $booking = $this->db->fetchAssoc($bookingQuery);
            
            // Calculate driver fee
            $start = new DateTime($booking['start_date']);
            $end = new DateTime($booking['end_date']);
            $days = $start->diff($end)->days + 1;
            $driver_fee = 500 * $days;
            
            // Update booking with driver
            $new_total = $booking['total_price'] + $driver_fee;
            $update = "UPDATE booking SET driver_id = '$driver_id', driver_fee = '$driver_fee', total_price = '$new_total', booking_status = 'Confirmed' WHERE booking_id = '$booking_id'";
            
            if ($this->db->query($update)) {
                // Update driver availability
                $this->db->query("UPDATE driver SET availability = 'Unavailable' WHERE driver_id = '$driver_id'");
                $this->setFlash('success', 'Driver assigned successfully!');
                $this->redirect('/admin/driver-assignment');
            } else {
                $this->setFlash('error', 'Assignment failed');
            }
        }
        
        // Handle driver removal
        if (isset($_GET['remove_driver']) && isset($_GET['booking_id'])) {
            $booking_id = intval($_GET['booking_id']);
            
            // Get current driver
            $bookingQuery = $this->db->query("SELECT driver_id, driver_fee FROM booking WHERE booking_id = '$booking_id'");
            $booking = $this->db->fetchAssoc($bookingQuery);
            
            if ($booking && $booking['driver_id']) {
                // Update booking
                $new_total = $booking['total_price'] - $booking['driver_fee'];
                $update = "UPDATE booking SET driver_id = NULL, driver_fee = 0, total_price = '$new_total', booking_status = 'Driver_Requested' WHERE booking_id = '$booking_id'";
                
                if ($this->db->query($update)) {
                    // Update driver availability
                    $this->db->query("UPDATE driver SET availability = 'Available' WHERE driver_id = '{$booking['driver_id']}'");
                    $this->setFlash('success', 'Driver removed successfully!');
                    $this->redirect('/admin/driver-assignment');
                }
            }
        }
        
        // Bookings needing drivers
        $needDriverSql = "SELECT b.*, c.full_name as customer_name, v.vehicle_name, v.vehicle_type 
                         FROM booking b 
                         LEFT JOIN customer c ON b.customer_id = c.customer_id 
                         LEFT JOIN vehicle v ON b.vehicle_id = v.vehicle_id 
                         WHERE b.booking_status = 'Driver_Requested' OR (b.driver_id IS NULL AND b.booking_status = 'Confirmed')
                         ORDER BY b.booking_date DESC";
        $needDriverResult = $this->db->query($needDriverSql);
        $needDriver = [];
        while ($row = $this->db->fetchAssoc($needDriverResult)) {
            $needDriver[] = $row;
        }
        
        // Active driver assignments
        $activeSql = "SELECT b.*, c.full_name as customer_name, v.vehicle_name, d.full_name as driver_name 
                      FROM booking b 
                      LEFT JOIN customer c ON b.customer_id = c.customer_id 
                      LEFT JOIN vehicle v ON b.vehicle_id = v.vehicle_id 
                      LEFT JOIN driver d ON b.driver_id = d.driver_id 
                      WHERE b.driver_id IS NOT NULL AND b.booking_status IN ('Confirmed', 'Driver_Requested')
                      ORDER BY b.booking_date DESC";
        $activeResult = $this->db->query($activeSql);
        $active = [];
        while ($row = $this->db->fetchAssoc($activeResult)) {
            $active[] = $row;
        }
        
        // Available drivers
        $availableDriversSql = "SELECT * FROM driver WHERE availability = 'Available' AND status = 'Active'";
        $availableDriversResult = $this->db->query($availableDriversSql);
        $availableDrivers = [];
        while ($row = $this->db->fetchAssoc($availableDriversResult)) {
            $availableDrivers[] = $row;
        }
        
        $this->render('admin/driver_assignment', [
            'needDriver' => $needDriver,
            'active' => $active,
            'availableDrivers' => $availableDrivers,
            'message' => $this->getFlash(),
            'userName' => 'Admin'
        ]);
    }
    
    /**
     * Display ratings overview for drivers and vehicles
     *
     * @return void
     */
    public function ratings() {
        $this->requireLogin('admin');
        
        // Get ratings statistics
        $avg_driver_rating = 0;
        $driverResult = $this->db->query("SELECT AVG(rating) as avg_rating FROM driver");
        if ($driverResult) {
            $row = $this->db->fetchAssoc($driverResult);
            $avg_driver_rating = $row['avg_rating'] ? round($row['avg_rating'], 2) : 0;
        }
        
        $total_drivers = 0;
        $driverCount = $this->db->query("SELECT COUNT(*) as count FROM driver");
        if ($driverCount) {
            $row = $this->db->fetchAssoc($driverCount);
            $total_drivers = $row['count'];
        }
        
        $avg_vehicle_rating = 0;
        $vehicleResult = $this->db->query("SELECT AVG(rating) as avg_rating FROM vehicle");
        if ($vehicleResult) {
            $row = $this->db->fetchAssoc($vehicleResult);
            $avg_vehicle_rating = $row['avg_rating'] ? round($row['avg_rating'], 2) : 0;
        }
        
        $total_vehicles = 0;
        $vehicleCount = $this->db->query("SELECT COUNT(*) as count FROM vehicle");
        if ($vehicleCount) {
            $row = $this->db->fetchAssoc($vehicleCount);
            $total_vehicles = $row['count'];
        }
        
        // Driver ratings
        $driverRatingsSql = "SELECT * FROM driver ORDER BY rating DESC";
        $driverRatingsResult = $this->db->query($driverRatingsSql);
        $driverRatings = [];
        while ($row = $this->db->fetchAssoc($driverRatingsResult)) {
            $driverRatings[] = $row;
        }
        
        // Vehicle ratings
        $vehicleRatingsSql = "SELECT * FROM vehicle ORDER BY rating DESC";
        $vehicleRatingsResult = $this->db->query($vehicleRatingsSql);
        $vehicleRatings = [];
        while ($row = $this->db->fetchAssoc($vehicleRatingsResult)) {
            $vehicleRatings[] = $row;
        }
        
        $this->render('admin/ratings', [
            'avg_driver_rating' => $avg_driver_rating,
            'total_drivers' => $total_drivers,
            'avg_vehicle_rating' => $avg_vehicle_rating,
            'total_vehicles' => $total_vehicles,
            'driverRatings' => $driverRatings,
            'vehicleRatings' => $vehicleRatings,
            'userName' => 'Admin'
        ]);
    }
}
?>