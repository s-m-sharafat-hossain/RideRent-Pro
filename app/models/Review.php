<?php
require_once __DIR__ . '/../core/Model.php';

/**
 * Review model for review-related database operations
 * 
 * @package RideRentPro\Models
 * @author RideRent Pro Team
 * @version 1.0.0
 */
class Review extends Model {
    /**
     * Database table name
     * @var string
     */
    protected $table = 'reviews';
    
    /**
     * Get all reviews with customer information
     * 
     * @return array Array of reviews
     */
    public function getAll() {
        $sql = "SELECT r.*, c.full_name as customer_name FROM reviews r 
                LEFT JOIN customer c ON r.user_id = c.customer_id 
                ORDER BY r.created_at DESC";
        return $this->query($sql);
    }
    
    /**
     * Get review by ID with customer information
     * 
     * @param int $id Review ID
     * @return array|false Review data or false if not found
     */
    public function getById($id) {
        $id = $this->db->escape($id);
        $sql = "SELECT r.*, c.full_name as customer_name FROM reviews r 
                LEFT JOIN customer c ON r.user_id = c.customer_id 
                WHERE r.review_id = '$id'";
        return $this->querySingle($sql);
    }
    
    /**
     * Get reviews by vehicle ID
     * 
     * @param int $vehicleId Vehicle ID
     * @return array Array of reviews
     */
    public function getByVehicle($vehicleId) {
        $vehicleId = $this->db->escape($vehicleId);
        $sql = "SELECT r.*, c.full_name as customer_name 
                FROM reviews r 
                LEFT JOIN customer c ON r.user_id = c.customer_id 
                WHERE r.target_type = 'vehicle' AND r.target_id = '$vehicleId'
                ORDER BY r.created_at DESC";
        return $this->query($sql);
    }
    
    /**
     * Get reviews by driver ID
     * 
     * @param int $driverId Driver ID
     * @return array Array of reviews
     */
    public function getByDriver($driverId) {
        $driverId = $this->db->escape($driverId);
        $sql = "SELECT r.*, c.full_name as customer_name 
                FROM reviews r 
                LEFT JOIN customer c ON r.user_id = c.customer_id 
                WHERE r.target_type = 'driver' AND r.target_id = '$driverId'
                ORDER BY r.created_at DESC";
        return $this->query($sql);
    }
    
    /**
     * Get reviews by customer ID
     * 
     * @param int $customerId Customer ID
     * @return array Array of reviews
     */
    public function getByCustomer($customerId) {
        $customerId = $this->db->escape($customerId);
        $sql = "SELECT r.* FROM reviews r 
                WHERE r.user_id = '$customerId'
                ORDER BY r.created_at DESC";
        return $this->query($sql);
    }
    
    /**
     * Get vehicle rating statistics
     * 
     * @param int $vehicleId Vehicle ID
     * @return array Rating data (avg_rating, total_reviews)
     */
    public function getVehicleRating($vehicleId) {
        $vehicleId = $this->db->escape($vehicleId);
        $sql = "SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews 
                FROM reviews 
                WHERE target_type = 'vehicle' AND target_id = '$vehicleId'";
        return $this->querySingle($sql);
    }
    
    /**
     * Get driver rating statistics
     * 
     * @param int $driverId Driver ID
     * @return array Rating data (avg_rating, total_reviews)
     */
    public function getDriverRating($driverId) {
        $driverId = $this->db->escape($driverId);
        $sql = "SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews 
                FROM reviews 
                WHERE target_type = 'driver' AND target_id = '$driverId'";
        return $this->querySingle($sql);
    }
    
    /**
     * Get overall review statistics
     * 
     * @return array Statistics (total, avg_rating)
     */
    public function getStats() {
        $stats = [];
        
        $result = $this->db->query("SELECT COUNT(*) as total FROM reviews");
        $row = $this->db->fetchAssoc($result);
        $stats['total'] = $row['total'];
        
        $result = $this->db->query("SELECT AVG(rating) as avg_rating FROM reviews");
        $row = $this->db->fetchAssoc($result);
        $stats['avg_rating'] = $row['avg_rating'] ? $row['avg_rating'] : 0;
        
        return $stats;
    }
}
?>