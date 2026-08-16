<?php
require_once __DIR__ . '/../core/Model.php';

/**
 * Driver model for driver-related database operations
 * 
 * @package RideRentPro\Models
 * @author RideRent Pro Team
 * @version 1.0.0
 */
class Driver extends Model {
    /**
     * Database table name
     * @var string
     */
    protected $table = 'driver';
    
    /**
     * Get all drivers with completed trips and average rating
     * 
     * @return array Array of drivers
     */
    public function getAll() {
        $sql = "SELECT d.*, (SELECT COUNT(*) FROM booking WHERE driver_id = d.driver_id AND booking_status = 'Completed') as completed_trips,
                (SELECT AVG(rating) FROM reviews WHERE driver_id = d.driver_id) as avg_rating
                FROM driver d 
                ORDER BY d.created_at DESC";
        return $this->query($sql);
    }
    
    /**
     * Get available drivers
     * 
     * @return array Array of available drivers
     */
    public function getAvailableDrivers() {
        $sql = "SELECT d.*, (SELECT COUNT(*) FROM booking WHERE driver_id = d.driver_id AND booking_status = 'Completed') as completed_trips,
                (SELECT AVG(rating) FROM reviews WHERE driver_id = d.driver_id) as avg_rating
                FROM driver d 
                WHERE d.availability_status = 'Available'
                ORDER BY d.created_at DESC";
        return $this->query($sql);
    }
    
    /**
     * Get driver by ID with completed trips and average rating
     * 
     * @param int $id Driver ID
     * @return array|false Driver data or false if not found
     */
    public function getById($id) {
        $id = $this->db->escape($id);
        $sql = "SELECT d.*, (SELECT COUNT(*) FROM booking WHERE driver_id = d.driver_id AND booking_status = 'Completed') as completed_trips,
                (SELECT AVG(rating) FROM reviews WHERE driver_id = d.driver_id) as avg_rating
                FROM driver d 
                WHERE d.driver_id = '$id'";
        return $this->querySingle($sql);
    }
    
    /**
     * Get drivers by owner ID
     * 
     * @param int $ownerId Owner ID
     * @return array Array of drivers
     */
    public function getByOwner($ownerId) {
        $ownerId = $this->db->escape($ownerId);
        $sql = "SELECT d.* FROM driver d 
                WHERE d.owner_id = '$ownerId'
                ORDER BY d.created_at DESC";
        return $this->query($sql);
    }
    
    /**
     * Update driver availability status
     * 
     * @param int $id Driver ID
     * @param string $status New availability status
     * @return mysqli_result|false Query result
     */
    public function updateAvailability($id, $status) {
        $id = $this->db->escape($id);
        $status = $this->db->escape($status);
        $sql = "UPDATE driver SET availability = '$status' WHERE driver_id = '$id'";
        return $this->db->query($sql);
    }
    
    /**
     * Update driver verification status
     * 
     * @param int $id Driver ID
     * @param string $status New verification status
     * @return mysqli_result|false Query result
     */
    public function updateVerification($id, $status) {
        $id = $this->db->escape($id);
        $status = $this->db->escape($status);
        $sql = "UPDATE driver SET verification_status = '$status' WHERE driver_id = '$id'";
        return $this->db->query($sql);
    }

    /**
     * Update editable profile fields for a driver
     *
     * @param int $id Driver ID
     * @param array $data Driver profile data
     * @return mysqli_result|false Query result
     */
    public function updateProfile($id, $data) {
        $id = $this->db->escape($id);

        $fullName = $this->db->escape($data['full_name']);
        $phone = $this->db->escape($data['phone']);
        $address = $this->db->escape($data['address']);
        $experienceYears = (int) $data['experience_years'];

        $sql = "UPDATE driver
                SET full_name = '$fullName',
                    phone = '$phone',
                    address = '$address',
                    experience_years = '$experienceYears'
                WHERE driver_id = '$id'";

        return $this->db->query($sql);
    }
    
    /**
     * Get driver earnings
     * 
     * @param int $driverId Driver ID
     * @return array Earnings data (total_earnings, total_trips)
     */
    public function getEarnings($driverId) {
        $driverId = $this->db->escape($driverId);
        $sql = "SELECT SUM(total_price) as total_earnings, COUNT(*) as total_trips 
                FROM booking 
                WHERE driver_id = '$driverId' AND payment_status = 'Paid' AND booking_status = 'Completed'";
        return $this->querySingle($sql);
    }
    
    /**
     * Get driver performance metrics
     * 
     * @param int $driverId Driver ID
     * @return array Performance data (total_bookings, completed_bookings, cancelled_bookings, avg_rating)
     */
    public function getPerformance($driverId) {
        $driverId = $this->db->escape($driverId);
        $sql = "SELECT 
                COUNT(*) as total_bookings,
                SUM(CASE WHEN booking_status = 'Completed' THEN 1 ELSE 0 END) as completed_bookings,
                SUM(CASE WHEN booking_status = 'Cancelled' THEN 1 ELSE 0 END) as cancelled_bookings,
                AVG(rating) as avg_rating
                FROM booking b
                LEFT JOIN review r ON b.booking_id = r.booking_id
                WHERE b.driver_id = '$driverId'";
        return $this->querySingle($sql);
    }
}
?>