<?php
require_once __DIR__ . '/../core/Model.php';

/**
 * Booking model for booking-related database operations
 * 
 * @package RideRentPro\Models
 * @author RideRent Pro Team
 * @version 1.0.0
 */
class Booking extends Model {
    /**
     * Database table name
     * @var string
     */
    protected $table = 'booking';
    
    /**
     * Get all bookings with related information
     * 
     * @return array Array of bookings
     */
    public function getAll() {
        $sql = "SELECT b.*, c.full_name as customer_name, v.vehicle_name, d.full_name as driver_name 
                FROM booking b 
                LEFT JOIN customer c ON b.customer_id = c.customer_id 
                LEFT JOIN vehicle v ON b.vehicle_id = v.vehicle_id 
                LEFT JOIN driver d ON b.driver_id = d.driver_id 
                ORDER BY b.booking_date DESC";
        return $this->query($sql);
    }
    
    /**
     * Get booking by ID with related information
     * 
     * @param int $id Booking ID
     * @return array|false Booking data or false if not found
     */
    public function getById($id) {
        $id = $this->db->escape($id);
        $sql = "SELECT b.*, c.full_name as customer_name, c.email as customer_email, c.phone_1 as customer_phone,
                v.vehicle_name, v.vehicle_type, v.price_per_day, v.location,
                d.full_name as driver_name, d.phone as driver_phone, d.license_number
                FROM booking b 
                LEFT JOIN customer c ON b.customer_id = c.customer_id 
                LEFT JOIN vehicle v ON b.vehicle_id = v.vehicle_id 
                LEFT JOIN driver d ON b.driver_id = d.driver_id 
                WHERE b.booking_id = '$id'";
        return $this->querySingle($sql);
    }
    
    /**
     * Get bookings by customer ID
     * 
     * @param int $customerId Customer ID
     * @return array Array of bookings
     */
    public function getByCustomer($customerId) {
        $customerId = $this->db->escape($customerId);
        $sql = "SELECT b.*, v.vehicle_name, d.full_name as driver_name 
                FROM booking b 
                LEFT JOIN vehicle v ON b.vehicle_id = v.vehicle_id 
                LEFT JOIN driver d ON b.driver_id = d.driver_id 
                WHERE b.customer_id = '$customerId'
                ORDER BY b.booking_date DESC";
        return $this->query($sql);
    }
    
    /**
     * Get bookings by owner ID
     * 
     * @param int $ownerId Owner ID
     * @return array Array of bookings
     */
    public function getByOwner($ownerId) {
        $ownerId = $this->db->escape($ownerId);
        $sql = "SELECT b.*, c.full_name as customer_name, v.vehicle_name 
                FROM booking b 
                LEFT JOIN customer c ON b.customer_id = c.customer_id 
                LEFT JOIN vehicle v ON b.vehicle_id = v.vehicle_id 
                WHERE v.owner_id = '$ownerId'
                ORDER BY b.booking_date DESC";
        return $this->query($sql);
    }
    
    /**
     * Get bookings by driver ID
     * 
     * @param int $driverId Driver ID
     * @return array Array of bookings
     */
    public function getByDriver($driverId) {
        $driverId = $this->db->escape($driverId);
        $sql = "SELECT b.*, c.full_name as customer_name, v.vehicle_name 
                FROM booking b 
                LEFT JOIN customer c ON b.customer_id = c.customer_id 
                LEFT JOIN vehicle v ON b.vehicle_id = v.vehicle_id 
                WHERE b.driver_id = '$driverId'
                ORDER BY b.booking_date DESC";
        return $this->query($sql);
    }
    
    /**
     * Get recent bookings
     * 
     * @param int $limit Number of bookings to return
     * @return array Array of recent bookings
     */
    public function getRecent($limit = 5) {
        $limit = (int)$limit;
        $sql = "SELECT b.*, c.full_name as customer_name, v.vehicle_name 
                FROM booking b 
                LEFT JOIN customer c ON b.customer_id = c.customer_id 
                LEFT JOIN vehicle v ON b.vehicle_id = v.vehicle_id 
                ORDER BY b.booking_date DESC LIMIT $limit";
        return $this->query($sql);
    }
    
    /**
     * Update booking status
     * 
     * @param int $id Booking ID
     * @param string $status New status
     * @return mysqli_result|false Query result
     */
    public function updateStatus($id, $status) {
        $id = $this->db->escape($id);
        $status = $this->db->escape($status);
        $sql = "UPDATE booking SET booking_status = '$status' WHERE booking_id = '$id'";
        return $this->db->query($sql);
    }
    
    /**
     * Update payment status
     * 
     * @param int $id Booking ID
     * @param string $status New payment status
     * @return mysqli_result|false Query result
     */
    public function updatePaymentStatus($id, $status) {
        $id = $this->db->escape($id);
        $status = $this->db->escape($status);
        $sql = "UPDATE booking SET payment_status = '$status' WHERE booking_id = '$id'";
        return $this->db->query($sql);
    }
    
    /**
     * Assign driver to booking
     * 
     * @param int $bookingId Booking ID
     * @param int $driverId Driver ID
     * @return mysqli_result|false Query result
     */
    public function assignDriver($bookingId, $driverId) {
        $bookingId = $this->db->escape($bookingId);
        $driverId = $this->db->escape($driverId);
        $sql = "UPDATE booking SET driver_id = '$driverId' WHERE booking_id = '$bookingId'";
        return $this->db->query($sql);
    }
    
    /**
     * Get booking statistics
     * 
     * @return array Statistics (total, pending, confirmed, completed, revenue)
     */
    public function getStats() {
        $stats = [];
        
        $result = $this->db->query("SELECT COUNT(*) as total FROM booking");
        $row = $this->db->fetchAssoc($result);
        $stats['total'] = $row['total'];
        
        $result = $this->db->query("SELECT COUNT(*) as total FROM booking WHERE booking_status = 'Pending'");
        $row = $this->db->fetchAssoc($result);
        $stats['pending'] = $row['total'];
        
        $result = $this->db->query("SELECT COUNT(*) as total FROM booking WHERE booking_status = 'Confirmed'");
        $row = $this->db->fetchAssoc($result);
        $stats['confirmed'] = $row['total'];
        
        $result = $this->db->query("SELECT COUNT(*) as total FROM booking WHERE booking_status = 'Completed'");
        $row = $this->db->fetchAssoc($result);
        $stats['completed'] = $row['total'];
        
        $result = $this->db->query("SELECT SUM(total_price) as total FROM booking WHERE payment_status = 'Paid'");
        $row = $this->db->fetchAssoc($result);
        $stats['revenue'] = $row['total'] ? $row['total'] : 0;
        
        return $stats;
    }
}
?>