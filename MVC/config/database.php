<?php
/**
 * Database Configuration File
 * 
 * This file establishes the database connection for the RideRentPro application.
 * It uses MySQLi extension to connect to the MySQL database.
 * 
 * @package RideRentPro\Config
 * @author RideRent Pro Team
 * @version 1.0.0
 */

// Database Configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "riderent_prodb";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Set charset
mysqli_set_charset($conn, "utf8mb4");
?>