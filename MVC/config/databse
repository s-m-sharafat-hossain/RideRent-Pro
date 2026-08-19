<?php
/**
 * Database connection configuration for the RideRent Pro application.
 *
 * This file is the canonical database bootstrap used by the MVC project.
 * It keeps the application connected to the XAMPP MySQL database using the
 * database structure supplied in the SQL import file.
 */

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'riderent_prodb';

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');
