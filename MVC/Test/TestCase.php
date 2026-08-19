<?php
/**
 * Base test case class for all tests
 * Provides common setup, teardown, and helper methods
 */

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase {
    
    /**
     * Mock database connection
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $dbMock;
    
    /**
     * Set up test environment
     */
    protected function setUp(): void {
        parent::setUp();
        
        // Reset $_SESSION for each test
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
            session_start();
        }
        $_SESSION = [];
    }
    
    /**
     * Tear down after each test
     */
    protected function tearDown(): void {
        parent::tearDown();
        $_SESSION = [];
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }
    
    /**
     * Helper: Create test customer data
     * @param array $overrides Override default values
     * @return array Customer test data
     */
    protected function createTestCustomer($overrides = []) {
        return array_merge([
            'customer_id' => 1,
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'phone_1' => '1234567890',
            'address' => '123 Main St',
            'city' => 'New York',
            'profile_picture' => null,
            'created_at' => date('Y-m-d H:i:s')
        ], $overrides);
    }
    
    /**
     * Helper: Create test booking data
     * @param array $overrides Override default values
     * @return array Booking test data
     */
    protected function createTestBooking($overrides = []) {
        return array_merge([
            'booking_id' => 1,
            'customer_id' => 1,
            'vehicle_id' => 1,
            'driver_id' => null,
            'start_date' => date('Y-m-d', strtotime('+1 day')),
            'end_date' => date('Y-m-d', strtotime('+3 days')),
            'booking_status' => 'Pending',
            'payment_status' => 'Unpaid',
            'total_price' => 300.00,
            'booking_date' => date('Y-m-d H:i:s')
        ], $overrides);
    }
    
    /**
     * Helper: Create test vehicle data
     * @param array $overrides Override default values
     * @return array Vehicle test data
     */
    protected function createTestVehicle($overrides = []) {
        return array_merge([
            'vehicle_id' => 1,
            'vehicle_name' => 'Toyota Corolla',
            'vehicle_type' => 'Sedan',
            'price_per_day' => 100.00,
            'owner_id' => 1,
            'location' => 'New York',
            'status' => 'Active',
            'created_at' => date('Y-m-d H:i:s')
        ], $overrides);
    }
    
    /**
     * Helper: Create test review data
     * @param array $overrides Override default values
     * @return array Review test data
     */
    protected function createTestReview($overrides = []) {
        return array_merge([
            'review_id' => 1,
            'booking_id' => 1,
            'customer_id' => 1,
            'rating' => 5,
            'comment' => 'Great service!',
            'review_date' => date('Y-m-d H:i:s')
        ], $overrides);
    }
    
    /**
     * Helper: Create test driver data
     * @param array $overrides Override default values
     * @return array Driver test data
     */
    protected function createTestDriver($overrides = []) {
        return array_merge([
            'driver_id' => 1,
            'full_name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'phone' => '9876543210',
            'license_number' => 'DL123456',
            'status' => 'Active'
        ], $overrides);
    }
}
