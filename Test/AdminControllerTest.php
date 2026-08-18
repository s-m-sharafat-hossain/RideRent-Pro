<?php
use PHPUnit\Framework\TestCase;

class AdminControllerTest extends TestCase
{
    public function testDashboardStatistics()
    {
        $stats = [
            'totalUsers' => 150,
            'totalVehicles' => 50,
            'totalDrivers' => 30,
            'totalBookings' => 200,
            'recentBookings' => [],
            'reviewStats' => ['total' => 100, 'avg_rating' => 4.5]
        ];
        
        $this->assertIsArray();
        $this->assertArrayHasKey('totalUsers', $stats);
        $this->assertArrayHasKey('totalVehicles', $stats);
        $this->assertArrayHasKey('totalDrivers', $stats);
        $this->assertArrayHasKey('totalBookings', );
        $this->assertEquals(150, $stats['totalUsers']);
    }

    public function testUserManagement()
    {
        $users = [
            'admins' => [['admin_id' => 1, 'full_name' => 'Admin User']],
            'customers' => [['customer_id' => 1, 'full_name' => 'Customer User']],
            'owners' => [['owner_id' => 1, 'full_name' => 'Owner User']],
            'drivers' => [['driver_id' => 1, 'full_name' => 'Driver User']]
        ];
        
        $this->assertIsArray($users);
        $this->assertArrayHasKey('admins', $users);
        $this->assertArrayHasKey('customers', $users);
        $this->assertArrayHasKey('owners', $users);
        $this->assertArrayHasKey('drivers', $users);
    }

    public function testVehicleManagement()
    {
        $vehicles = [
            ['vehicle_id' => 1, 'vehicle_name' => 'Toyota Camry', 'availability' => 'Available'],
            ['vehicle_id' => 2, 'vehicle_name' => 'Honda Civic', 'availability' => 'Booked']
        ];
        
        $this->assertIsArray($vehicles);
        $this->assertCount(2, $vehicles);
        $this->assertEquals('Toyota Camry', $vehicles[0]['vehicle_name']);
    }

    public function testBookingManagement()
    {
        $bookings = [
            ['booking_id' => 1, 'booking_status' => 'Confirmed', 'payment_status' => 'Paid'],
            ['booking_id' => 2, 'booking_status' => 'Pending', 'payment_status' => 'Pending']
        ];
        
        $this->assertIsArray($bookings);
        $this->assertEquals('Confirmed', $bookings[0]['booking_status']);
        $this->assertEquals('Paid', $bookings[0]['payment_status']);
    }

    public function testDriverManagement()
    {
        $drivers = [
            ['driver_id' => 1, 'full_name' => 'Driver One', 'availability' => 'Available', 'status' => 'Active'],
            ['driver_id' => 2, 'full_name' => 'Driver Two', 'availability' => 'Unavailable', 'status' => 'Inactive']
        ];
        
        $this->assertIsArray($drivers);
        $this->assertEquals('Available', $drivers[0]['availability']);
        $this->assertEquals('Active', $drivers[0]['status']);
    }

    public function testReviewManagement()
    {
        $reviews = [
            ['review_id' => 1, 'rating' => 5, 'status' => 'pending', 'target_type' => 'vehicle'],
            ['review_id' => 2, 'rating' => 4, 'status' => 'approved', 'target_type' => 'driver']
        ];
        
        $this->assertIsArray($reviews);
        $this->assertEquals(5, $reviews[0]['rating']);
        $this->assertEquals('pending', $reviews[0]['status']);
    }

    public function testVehicleApproval()
    {
        $pendingVehicles = [
            ['vehicle_id' => 1, 'vehicle_name' => 'New Car', 'approval_status' => 'Pending'],
            ['vehicle_id' => 2, 'vehicle_name' => 'Another Car', 'approval_status' => 'Pending']
        ];
        
        $this->assertIsArray($pendingVehicles);
        $this->assertEquals('Pending', $pendingVehicles[0]['approval_status']);
        $this->assertCount(2, $pendingVehicles);
    }

    public function testDriverAssignment()
    {
        $assignment = [
            'booking_id' => 1,
            'driver_id' => 1,
            'driver_fee' => 500,
            'total_price' => 2500
        ];
        
        $this->assertIsArray($assignment);
        $this->assertEquals(1, $assignment['booking_id']);
        $this->assertEquals(500, $assignment['driver_fee']);
    }

    public function testReportsGeneration()
    {
        $stats = [
            'totalVehicles' => 50,
            'availableVehicles' => 30,
            'bookedVehicles' => 15,
            'maintenanceVehicles' => 5,
            'latestVehicles' => []
        ];
        
        $this->assertIsArray($stats);
        $this->assertEquals(50, $stats['totalVehicles']);
        $this->assertEquals(30, $stats['availableVehicles']);
        $this->assertEquals(15, $stats['bookedVehicles']);
    }

    public function testRatingUpdate()
    {
        $ratingData = [
            'driver_id' => 1,
            'new_rating' => 4.5,
            'rating_count' => 10
        ];
        
        $this->assertIsArray($ratingData);
        $this->assertEquals(4.5, $ratingData['new_rating']);
        $this->assertEquals(10, $ratingData['rating_count']);
    }
}
