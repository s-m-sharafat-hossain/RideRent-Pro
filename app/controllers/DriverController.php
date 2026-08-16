<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/Booking.php';
require_once __DIR__ . '/../models/Driver.php';

/**
 * Driver Controller for handling driver portal requests
 * 
 * @package RideRentPro\Controllers
 * @author RideRent Pro Team
 * @version 1.0.0
 */
class DriverController extends Controller {
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
     * Database instance
     * @var Database
     */
    private $db;
    
    /**
     * Constructor - Initialize all models and database
     */
    public function __construct() {
        parent::__construct();
        $this->bookingModel = new Booking();
        $this->driverModel = new Driver();
        $this->db = Database::getInstance();
    }
    
    public function dashboard() {
        $this->requireLogin('driver');
        
        $driverId = $_SESSION['driver_id'];
        $driverName = $_SESSION['driver_name'];
        
        // Get driver's bookings
        $totalBookings = 0;
        $bookingQuery = $this->db->query("SELECT * FROM booking WHERE driver_id = '$driverId'");
        if ($bookingQuery) { $totalBookings = $this->db->numRows($bookingQuery); }
        
        // Get active bookings
        $activeBookings = 0;
        $activeQuery = $this->db->query("SELECT * FROM booking WHERE driver_id = '$driverId' AND booking_status IN ('Confirmed', 'Driver_Requested')");
        if ($activeQuery) { $activeBookings = $this->db->numRows($activeQuery); }
        
        // Get completed bookings
        $completedBookings = 0;
        $completedQuery = $this->db->query("SELECT * FROM booking WHERE driver_id = '$driverId' AND booking_status = 'Completed'");
        if ($completedQuery) { $completedBookings = $this->db->numRows($completedQuery); }
        
        // Get total earnings
        $totalEarnings = 0;
        $earningsQuery = $this->db->query("SELECT SUM(driver_fee) as total FROM booking WHERE driver_id = '$driverId' AND payment_status = 'Paid'");
        if ($earningsQuery) {
            $row = $this->db->fetchAssoc($earningsQuery);
            $totalEarnings = $row['total'] ? $row['total'] : 0;
        }
        
        // Get pending earnings
        $pendingEarnings = 0;
        $pendingQuery = $this->db->query("SELECT SUM(driver_fee) as total FROM booking WHERE driver_id = '$driverId' AND payment_status = 'Pending'");
        if ($pendingQuery) {
            $row = $this->db->fetchAssoc($pendingQuery);
            $pendingEarnings = $row['total'] ? $row['total'] : 0;
        }
        
        // Get driver info
        $driverInfoSql = "SELECT * FROM driver WHERE driver_id = '$driverId'";
        $driverInfoResult = $this->db->query($driverInfoSql);
        $driverInfo = $this->db->fetchAssoc($driverInfoResult);
        
        // Get recent bookings
        $recentBookingsSql = "SELECT b.*, c.full_name AS customer_name, c.phone_1 AS customer_phone, v.vehicle_name, v.vehicle_type, v.brand
                              FROM booking b
                              LEFT JOIN customer c ON b.customer_id = c.customer_id
                              LEFT JOIN vehicle v ON b.vehicle_id = v.vehicle_id
                              WHERE b.driver_id = '$driverId'
                              ORDER BY b.booking_id DESC LIMIT 5";
        $recentBookingsResult = $this->db->query($recentBookingsSql);
        $recentBookings = [];
        while ($row = $this->db->fetchAssoc($recentBookingsResult)) {
            $recentBookings[] = $row;
        }
        
        $this->render('driver/dashboard', [
            'totalBookings' => $totalBookings,
            'activeBookings' => $activeBookings,
            'completedBookings' => $completedBookings,
            'totalEarnings' => $totalEarnings,
            'pendingEarnings' => $pendingEarnings,
            'driverInfo' => $driverInfo,
            'recentBookings' => $recentBookings,
            'userName' => $driverName,
            'userRole' => 'Driver'
        ]);
    }
    
    public function bookings() {
        $this->requireLogin('driver');
        
        $driverId = $_SESSION['driver_id'];
        $bookings = $this->bookingModel->getByDriver($driverId);
        
        $this->render('driver/bookings', [
            'bookings' => $bookings ?: [],
            'userName' => $_SESSION['driver_name']
        ]);
    }
    
    public function bookingDetails() {
        $this->requireLogin('driver');
        
        $bookingId = $_GET['id'] ?? null;
        $booking = $bookingId ? $this->bookingModel->getById($bookingId) : null;
        
        if (!$booking || $booking['driver_id'] != $_SESSION['driver_id']) {
            $this->setFlash('error', 'Booking not found');
            $this->redirect('/driver/bookings');
        }
        
        $this->render('driver/booking_details', [
            'booking' => $booking,
            'userName' => $_SESSION['driver_name']
        ]);
    }
    
    public function availability() {
        $this->requireLogin('driver');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = $this->sanitize($_POST['availability_status']);
            $driverId = $_SESSION['driver_id'];
            
            if ($this->driverModel->updateAvailability($driverId, $status)) {
                $this->setFlash('success', 'Availability updated successfully!');
            } else {
                $this->setFlash('error', 'Failed to update availability');
            }
        }
        
        $driverId = $_SESSION['driver_id'];
        $driver = $this->driverModel->getById($driverId);
        
        $this->render('driver/availability', [
            'driver' => $driver,
            'userName' => $_SESSION['driver_name']
        ]);
    }
    
    public function earnings() {
        $this->requireLogin('driver');
        
        $driverId = $_SESSION['driver_id'];
        
        // Get earnings data
        $paidEarnings = 0;
        $paidCount = 0;
        $paidQuery = $this->db->query("SELECT SUM(driver_fee) as total, COUNT(*) as count FROM booking WHERE driver_id = '$driverId' AND payment_status = 'Paid'");
        if ($paidQuery) {
            $row = $this->db->fetchAssoc($paidQuery);
            $paidEarnings = $row['total'] ? $row['total'] : 0;
            $paidCount = $row['count'] ? $row['count'] : 0;
        }
        
        $pendingEarnings = 0;
        $pendingCount = 0;
        $pendingQuery = $this->db->query("SELECT SUM(driver_fee) as total, COUNT(*) as count FROM booking WHERE driver_id = '$driverId' AND payment_status = 'Pending'");
        if ($pendingQuery) {
            $row = $this->db->fetchAssoc($pendingQuery);
            $pendingEarnings = $row['total'] ? $row['total'] : 0;
            $pendingCount = $row['count'] ? $row['count'] : 0;
        }
        
        $totalEarnings = $paidEarnings + $pendingEarnings;
        
        // Get payment history
        $historySql = "SELECT b.*, c.full_name AS customer_name, v.vehicle_name
                       FROM booking b
                       LEFT JOIN customer c ON b.customer_id = c.customer_id
                       LEFT JOIN vehicle v ON b.vehicle_id = v.vehicle_id
                       WHERE b.driver_id = '$driverId'
                       ORDER BY b.booking_date DESC";
        $historyResult = $this->db->query($historySql);
        $history = [];
        while ($row = $this->db->fetchAssoc($historyResult)) {
            $history[] = $row;
        }
        
        $this->render('driver/earnings', [
            'totalEarnings' => $totalEarnings,
            'paidEarnings' => $paidEarnings,
            'pendingEarnings' => $pendingEarnings,
            'paidCount' => $paidCount,
            'pendingCount' => $pendingCount,
            'history' => $history,
            'userName' => $_SESSION['driver_name']
        ]);
    }
    
    public function performance() {
        $this->requireLogin('driver');
        
        $driverId = $_SESSION['driver_id'];
        
        // Get driver info
        $driverInfoSql = "SELECT * FROM driver WHERE driver_id = '$driverId'";
        $driverInfoResult = $this->db->query($driverInfoSql);
        $driverInfo = $this->db->fetchAssoc($driverInfoResult);
        
        // Performance metrics
        $total_bookings = 0;
        $completed_bookings = 0;
        $cancelled_bookings = 0;
        $total_earnings = 0;
        
        $statsQuery = $this->db->query("SELECT * FROM booking WHERE driver_id = '$driverId'");
        if ($statsQuery) {
            while ($row = $this->db->fetchAssoc($statsQuery)) {
                $total_bookings++;
                if ($row['booking_status'] == 'Completed') {
                    $completed_bookings++;
                    $total_earnings += $row['driver_fee'];
                } elseif ($row['booking_status'] == 'Cancelled') {
                    $cancelled_bookings++;
                }
            }
        }
        
        // Calculate completion rate
        $completion_rate = $total_bookings > 0 ? round(($completed_bookings / $total_bookings) * 100, 1) : 0;
        
        // Get recent reviews
        $reviewsSql = "SELECT r.*, c.full_name AS customer_name FROM reviews r 
                       LEFT JOIN customer c ON r.user_id = c.customer_id 
                       WHERE r.target_type = 'driver' AND r.target_id = '$driverId' AND r.status = 'approved'
                       ORDER BY r.created_at DESC LIMIT 5";
        $reviewsResult = $this->db->query($reviewsSql);
        $reviews = [];
        while ($row = $this->db->fetchAssoc($reviewsResult)) {
            $reviews[] = $row;
        }
        
        // Monthly earnings data
        $monthly_earnings = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = date('m', strtotime("-$i months"));
            $year = date('Y', strtotime("-$i months"));
            $month_name = date('M', strtotime("-$i months"));
            
            $earningsSql = "SELECT SUM(driver_fee) as total FROM booking 
                           WHERE driver_id = '$driverId' AND payment_status = 'Paid' 
                           AND MONTH(start_date) = '$month' AND YEAR(start_date) = '$year'";
            $earningsResult = $this->db->query($earningsSql);
            $earningsRow = $this->db->fetchAssoc($earningsResult);
            
            $monthly_earnings[] = [
                'month' => $month_name,
                'earnings' => $earningsRow['total'] ? $earningsRow['total'] : 0
            ];
        }
        
        $this->render('driver/performance', [
            'driverInfo' => $driverInfo,
            'total_bookings' => $total_bookings,
            'completed_bookings' => $completed_bookings,
            'cancelled_bookings' => $cancelled_bookings,
            'total_earnings' => $total_earnings,
            'completion_rate' => $completion_rate,
            'reviews' => $reviews,
            'monthly_earnings' => $monthly_earnings,
            'userName' => $_SESSION['driver_name']
        ]);
    }
    
    public function profile() {
        $this->requireLogin('driver');
        
        $driverId = $_SESSION['driver_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
            $profileData = [
                'full_name' => $this->sanitize($_POST['full_name']),
                'phone' => $this->sanitize($_POST['phone']),
                'address' => $this->sanitize($_POST['address']),
                'experience_years' => $this->sanitize($_POST['experience_years'])
            ];

            if ($this->driverModel->updateProfile($driverId, $profileData)) {
                $_SESSION['driver_name'] = $profileData['full_name'];
                $this->setFlash('success', 'Profile updated successfully.');
            } else {
                $this->setFlash('error', 'Failed to update profile. Please try again.');
            }

            $this->redirect('/driver/profile');
        }

        $driver = $this->driverModel->getById($driverId);

        $associationSql = "SELECT COUNT(DISTINCT b.vehicle_id) AS vehicle_count,
                                  GROUP_CONCAT(DISTINCT v.vehicle_name ORDER BY v.vehicle_name SEPARATOR ', ') AS vehicle_names
                           FROM booking b
                           LEFT JOIN vehicle v ON b.vehicle_id = v.vehicle_id
                           WHERE b.driver_id = '$driverId'";
        $associationResult = $this->db->query($associationSql);
        $vehicleAssociation = $this->db->fetchAssoc($associationResult);

        $recentVehicleSql = "SELECT v.vehicle_name, v.brand, v.model, b.booking_status
                             FROM booking b
                             LEFT JOIN vehicle v ON b.vehicle_id = v.vehicle_id
                             WHERE b.driver_id = '$driverId'
                             ORDER BY b.booking_id DESC
                             LIMIT 1";
        $recentVehicleResult = $this->db->query($recentVehicleSql);
        $recentVehicle = $this->db->fetchAssoc($recentVehicleResult);
        
        $this->render('driver/profile', [
            'driver' => $driver,
            'vehicleAssociation' => $vehicleAssociation ?: ['vehicle_count' => 0, 'vehicle_names' => null],
            'recentVehicle' => $recentVehicle,
            'flash' => $this->getFlash(),
            'userName' => $_SESSION['driver_name']
        ]);
    }
}
?>