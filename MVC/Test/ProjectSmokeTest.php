<?php
/**
 * Project smoke test for the RideRent Pro PHP application.
 *
 * This is a lightweight PHPUnit-style test runner written in pure PHP so it
 * works in XAMPP without requiring any external language or package manager.
 */

function assertTrue($condition, $message) {
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

function assertNotEmpty($value, $message) {
    assertTrue(!empty($value), $message);
}

try {
    require_once __DIR__ . '/../app/config/database.php';

    assertTrue(isset($conn), 'Database connection variable was not created.');
    assertTrue($conn instanceof mysqli, 'Database connection is not a valid MySQLi object.');
    assertTrue(mysqli_ping($conn), 'Database connection is not alive.');

    $result = $conn->query("SHOW TABLES LIKE 'vehicle_owner'");
    assertNotEmpty($result, 'The vehicle_owner table is missing.');
    assertTrue($result->num_rows > 0, 'The vehicle_owner table was not created.');

    $ownerResult = $conn->query("SELECT * FROM vehicle_owner WHERE email = 'masud@gmail.com'");
    assertNotEmpty($ownerResult, 'The default owner account is missing.');
    assertTrue($ownerResult->num_rows > 0, 'The default owner account was not imported into the database.');

    echo "Project smoke test passed. Database and seeded data are working.\n";
} catch (Throwable $e) {
    echo "Project smoke test failed: " . $e->getMessage() . "\n";
    exit(1);
}
