<?php
/**
 * Database singleton class for managing database connections
 * 
 * @package RideRentPro\Core
 * @author RideRent Pro Team
 * @version 1.0.0
 */
class Database {
    /**
     * Singleton instance
     * @var Database|null
     */
    private static $instance = null;
    
    /**
     * Database connection
     * @var mysqli
     */
    private $conn;
    
    /**
     * Private constructor to prevent direct instantiation
     */
    private function __construct() {
        require_once __DIR__ . '/../config/database.php';
        $this->conn = $conn;
    }
    
    /**
     * Get singleton instance
     * 
     * @return Database
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    /**
     * Get database connection
     * 
     * @return mysqli
     */
    public function getConnection() {
        return $this->conn;
    }
    
    /**
     * Execute SQL query
     * 
     * @param string $sql SQL query to execute
     * @return mysqli_result|false Query result or false on failure
     */
    public function query($sql) {
        $result = mysqli_query($this->conn, $sql);
        if ($result === false) {
            error_log("Database query error: " . mysqli_error($this->conn) . " - SQL: " . $sql);
        }
        return $result;
    }
    
    /**
     * Escape string for SQL
     * 
     * @param string $data Data to escape
     * @return string Escaped string
     */
    public function escape($data) {
        return mysqli_real_escape_string($this->conn, $data);
    }
    
    /**
     * Fetch result row as associative array
     * 
     * @param mysqli_result|false $result Query result
     * @return array|false Associative array or false on failure
     */
    public function fetchAssoc($result) {
        if ($result === false) {
            return false;
        }
        return mysqli_fetch_assoc($result);
    }
    
    /**
     * Get number of rows in result
     * 
     * @param mysqli_result $result Query result
     * @return int Number of rows
     */
    public function numRows($result) {
        return mysqli_num_rows($result);
    }
    
    /**
     * Get last insert ID
     * 
     * @return int Last inserted ID
     */
    public function insertId() {
        return mysqli_insert_id($this->conn);
    }
    
    /**
     * Get last database error
     * 
     * @return string Error message
     */
    public function error() {
        return mysqli_error($this->conn);
    }
}
?>