<?php
require_once __DIR__ . '/Database.php';

/**
 * Base Model class for database operations
 * 
 * @package RideRentPro\Core
 * @author RideRent Pro Team
 * @version 1.0.0
 */
class Model {
    /**
     * Database instance
     * @var Database
     */
    protected $db;
    
    /**
     * Table name
     * @var string
     */
    protected $table;
    
    /**
     * Constructor - Initialize database connection
     */
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get all records from table
     * 
     * @return array Array of all records
     */
    public function getAll() {
        $sql = "SELECT * FROM {$this->table}";
        $result = $this->db->query($sql);
        $data = [];
        while ($row = $this->db->fetchAssoc($result)) {
            $data[] = $row;
        }
        return $data;
    }
    
    /**
     * Get record by ID
     * 
     * @param int $id Record ID
     * @return array|false Record data or false if not found
     */
    public function getById($id) {
        $id = $this->db->escape($id);
        // Try to determine the primary key column name
        $primaryKey = $this->table . '_id';
        $sql = "SELECT * FROM {$this->table} WHERE $primaryKey = '$id'";
        $result = $this->db->query($sql);
        return $this->db->fetchAssoc($result);
    }
    
    /**
     * Create new record
     * 
     * @param array $data Associative array of column => value pairs
     * @return int|false Last insert ID or false on failure
     */
    public function create($data) {
        $columns = array_keys($data);
        $values = array_values($data);
        
        $escapedValues = array_map(function($val) {
            return "'" . $this->db->escape($val) . "'";
        }, $values);
        
        $columnsStr = implode(', ', $columns);
        $valuesStr = implode(', ', $escapedValues);
        
        $sql = "INSERT INTO {$this->table} ($columnsStr) VALUES ($valuesStr)";
        $result = $this->db->query($sql);
        
        if ($result) {
            return $this->db->insertId();
        }
        return false;
    }
    
    /**
     * Create record in specified table
     * 
     * @param string $table Table name
     * @param array $data Associative array of column => value pairs
     * @return int|false Last insert ID or false on failure
     */
    public function createInTable($table, $data) {
        $columns = array_keys($data);
        $values = array_values($data);
        
        $escapedValues = array_map(function($val) {
            return "'" . $this->db->escape($val) . "'";
        }, $values);
        
        $columnsStr = implode(', ', $columns);
        $valuesStr = implode(', ', $escapedValues);
        
        $sql = "INSERT INTO $table ($columnsStr) VALUES ($valuesStr)";
        $result = $this->db->query($sql);
        
        if ($result) {
            return $this->db->insertId();
        }
        return false;
    }
    
    /**
     * Update record by ID
     * 
     * @param int $id Record ID
     * @param array $data Associative array of column => value pairs
     * @return mysqli_result|false Query result
     */
    public function update($id, $data) {
        $id = $this->db->escape($id);
        $primaryKey = $this->table . '_id';
        $setParts = [];
        
        foreach ($data as $column => $value) {
            $escapedValue = "'" . $this->db->escape($value) . "'";
            $setParts[] = "$column = $escapedValue";
        }
        
        $setStr = implode(', ', $setParts);
        $sql = "UPDATE {$this->table} SET $setStr WHERE $primaryKey = '$id'";
        
        return $this->db->query($sql);
    }
    
    /**
     * Delete record by ID
     * 
     * @param int $id Record ID
     * @return mysqli_result|false Query result
     */
    public function delete($id) {
        $id = $this->db->escape($id);
        $primaryKey = $this->table . '_id';
        $sql = "DELETE FROM {$this->table} WHERE $primaryKey = '$id'";
        return $this->db->query($sql);
    }
    
    /**
     * Execute custom query and return all rows
     * 
     * @param string $sql SQL query
     * @return array Array of result rows
     */
    public function query($sql) {
        $result = $this->db->query($sql);
        $data = [];
        while ($row = $this->db->fetchAssoc($result)) {
            $data[] = $row;
        }
        return $data;
    }
    
    /**
     * Execute custom query and return single row
     * 
     * @param string $sql SQL query
     * @return array|false Single result row or false if not found
     */
    public function querySingle($sql) {
        $result = $this->db->query($sql);
        return $this->db->fetchAssoc($result);
    }
}
?>