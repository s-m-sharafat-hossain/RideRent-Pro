<?php
/**
 * Global Functions File
 * 
 * This file contains utility functions used throughout the RideRentPro application.
 * Functions include sanitization, redirects, authentication checks, date formatting,
 * currency formatting, and file upload handling.
 * 
 * @package RideRentPro\Config
 * @author RideRent Pro Team
 * @version 1.0.0
 */

/**
 * Sanitize input data to prevent SQL injection
 * 
 * @param string $data The data to sanitize
 * @return string The sanitized data
 */
function sanitize($data) {
    global $conn;
    return mysqli_real_escape_string($conn, $data);
}

/**
 * Redirect to a specified URL
 * 
 * @param string $url The target URL for redirection
 * @return void
 */
function redirect($url) {
    header("Location: $url");
    exit();
}

/**
 * Check if user is logged in
 * 
 * @return bool True if user is logged in, false otherwise
 */
function is_logged_in() {
    return isset($_SESSION['user_id']) || isset($_SESSION['admin_id']) || 
           isset($_SESSION['owner_id']) || isset($_SESSION['driver_id']) || 
           isset($_SESSION['customer_id']);
}

/**
 * Get current user role from session
 * 
 * @return string|null The user role (admin, owner, driver, customer) or null if not logged in
 */
function get_user_role() {
    if (isset($_SESSION['admin']) || isset($_SESSION['admin_id'])) return 'admin';
    if (isset($_SESSION['owner_id'])) return 'owner';
    if (isset($_SESSION['driver_id'])) return 'driver';
    if (isset($_SESSION['customer_id'])) return 'customer';
    return null;
}

/**
 * Format date to readable format
 * 
 * @param string $date The date string to format
 * @return string The formatted date (e.g., "January 1, 2024")
 */
function format_date($date) {
    return date('F j, Y', strtotime($date));
}

/**
 * Format amount to currency format
 * 
 * @param float $amount The amount to format
 * @return string The formatted currency string (e.g., "৳1,234.56")
 */
function format_currency($amount) {
    return '৳' . number_format($amount, 2);
}

/**
 * Handle file upload with validation
 * 
 * @param array $file The $_FILES array element for the uploaded file
 * @param string $target_dir The target directory for the file
 * @param array $allowed_types Array of allowed file extensions (default: jpg, jpeg, png, gif)
 * @param int $max_size Maximum file size in bytes (default: 2MB)
 * @return array Array with 'success' boolean and either 'filename' or 'message' key
 */
function upload_file($file, $target_dir, $allowed_types = ['jpg', 'jpeg', 'png', 'gif'], $max_size = 2097152) {
    if (empty($file['name'])) {
        return ['success' => false, 'message' => 'No file selected'];
    }
    
    $file_name = $file['name'];
    $file_size = $file['size'];
    $file_tmp = $file['tmp_name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    // Check file type
    if (!in_array($file_ext, $allowed_types)) {
        return ['success' => false, 'message' => 'Invalid file type'];
    }
    
    // Check file size
    if ($file_size > $max_size) {
        return ['success' => false, 'message' => 'File size too large'];
    }
    
    // Generate unique filename
    $new_file_name = time() . '_' . $file_name;
    $target_path = $target_dir . '/' . $new_file_name;
    
    // Move file
    if (move_uploaded_file($file_tmp, $target_path)) {
        return ['success' => true, 'filename' => $new_file_name];
    } else {
        return ['success' => false, 'message' => 'File upload failed'];
    }
}
?>