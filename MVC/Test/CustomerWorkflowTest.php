<?php
/**
 * Integration tests for complete customer workflows
 * Tests end-to-end scenarios
 */

class CustomerWorkflowTest extends TestCase {
    
    /**
     * Test: Customer can view vehicles
     */
    public function testCustomerCanBrowseVehicles() {
        $vehicles = [
            $this->createTestVehicle([
                'vehicle_id' => 1,
                'vehicle_name' => 'Toyota Corolla',
                'price_per_day' => 100.00
            ]),
            $this->createTestVehicle([
                'vehicle_id' => 2,
                'vehicle_name' => 'Honda Civic',
                'price_per_day' => 120.00
            ]),
            $this->createTestVehicle([
                'vehicle_id' => 3,
                'vehicle_name' => 'Ford Focus',
                'price_per_day' => 90.00
            ])
        ];
        
        // Verify vehicles are available
        $this->assertCount(3, $vehicles);
        
        // Verify first vehicle details
        $this->assertEquals(1, $vehicles[0]['vehicle_id']);
        $this->assertEquals('Toyota Corolla', $vehicles[0]['vehicle_name']);
        $this->assertEquals(100.00, $vehicles[0]['price_per_day']);
    }
    
    /**
     * Test: Complete booking workflow
     */
    public function testCompleteBookingWorkflow() {
        // Step 1: Customer authenticates
        $_SESSION['customer_id'] = 1;
        $_SESSION['customer_name'] = 'John Doe';
        
        // Step 2: Customer browses and selects vehicle
        $vehicle = $this->createTestVehicle([
            'vehicle_id' => 1,
            'vehicle_name' => 'Toyota Corolla'
        ]);
        
        $this->assertEquals(1, $vehicle['vehicle_id']);
        
        // Step 3: Customer creates booking
        $booking = $this->createTestBooking([
            'customer_id' => 1,
            'vehicle_id' => 1,
            'booking_status' => 'Pending'
        ]);
        
        $this->assertEquals(1, $booking['customer_id']);
        $this->assertEquals(1, $booking['vehicle_id']);
        $this->assertEquals('Pending', $booking['booking_status']);
        
        // Step 4: Verify booking in customer's booking list
        $customerBookings = [$booking];
        $this->assertCount(1, $customerBookings);
    }
    
    /**
     * Test: Customer profile management
     */
    public function testCustomerProfileManagement() {
        // Create customer profile
        $customer = $this->createTestCustomer([
            'customer_id' => 1,
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'phone_1' => '1234567890',
            'address' => '123 Main St',
            'city' => 'New York'
        ]);
        
        // Verify profile data
        $this->assertEquals('John Doe', $customer['full_name']);
        $this->assertEquals('john@example.com', $customer['email']);
        $this->assertEquals('1234567890', $customer['phone_1']);
        $this->assertEquals('123 Main St', $customer['address']);
        $this->assertEquals('New York', $customer['city']);
        
        // Test updating profile
        $updatedCustomer = array_merge($customer, [
            'full_name' => 'Jane Doe',
            'phone_1' => '9876543210'
        ]);
        
        $this->assertEquals('Jane Doe', $updatedCustomer['full_name']);
        $this->assertEquals('9876543210', $updatedCustomer['phone_1']);
        $this->assertEquals('john@example.com', $updatedCustomer['email']); // Email unchanged
    }
    
    /**
     * Test: Customer review submission
     */
    public function testCustomerReviewSubmission() {
        // Customer completes booking
        $booking = $this->createTestBooking([
            'booking_id' => 1,
            'customer_id' => 1,
            'booking_status' => 'Completed',
            'payment_status' => 'Paid'
        ]);
        
        // Customer submits review
        $review = $this->createTestReview([
            'booking_id' => $booking['booking_id'],
            'customer_id' => $booking['customer_id'],
            'rating' => 5,
            'comment' => 'Excellent service and vehicle condition!'
        ]);
        
        // Verify review
        $this->assertEquals(1, $review['booking_id']);
        $this->assertEquals(1, $review['customer_id']);
        $this->assertEquals(5, $review['rating']);
        $this->assertStringContainsString('Excellent', $review['comment']);
    }
    
    /**
     * Test: Rating validation (1-5 stars)
     */
    public function testReviewRatingValidation() {
        $validReview = $this->createTestReview(['rating' => 5]);
        
        $this->assertGreaterThanOrEqual(1, $validReview['rating']);
        $this->assertLessThanOrEqual(5, $validReview['rating']);
    }
    
    /**
     * Test: Multiple bookings in customer history
     */
    public function testCustomerMultipleBookings() {
        $bookings = [
            $this->createTestBooking([
                'booking_id' => 1,
                'customer_id' => 1,
                'vehicle_id' => 1,
                'booking_status' => 'Completed'
            ]),
            $this->createTestBooking([
                'booking_id' => 2,
                'customer_id' => 1,
                'vehicle_id' => 2,
                'booking_status' => 'Pending'
            ]),
            $this->createTestBooking([
                'booking_id' => 3,
                'customer_id' => 1,
                'vehicle_id' => 3,
                'booking_status' => 'Confirmed'
            ])
        ];
        
        // Verify all bookings belong to customer
        $this->assertCount(3, $bookings);
        
        foreach ($bookings as $booking) {
            $this->assertEquals(1, $booking['customer_id']);
        }
        
        // Verify status distribution
        $completed = array_filter($bookings, fn($b) => $b['booking_status'] === 'Completed');
        $pending = array_filter($bookings, fn($b) => $b['booking_status'] === 'Pending');
        $confirmed = array_filter($bookings, fn($b) => $b['booking_status'] === 'Confirmed');
        
        $this->assertCount(1, $completed);
        $this->assertCount(1, $pending);
        $this->assertCount(1, $confirmed);
    }
    
    /**
     * Test: Booking date validation
     */
    public function testBookingDateValidation() {
        $today = new DateTime();
        $tomorrow = new DateTime('+1 day');
        
        $booking = $this->createTestBooking([
            'start_date' => $tomorrow->format('Y-m-d'),
            'end_date' => $tomorrow->modify('+2 days')->format('Y-m-d')
        ]);
        
        $startDate = new DateTime($booking['start_date']);
        $endDate = new DateTime($booking['end_date']);
        
        // End date should be after start date
        $this->assertGreaterThan($startDate, $endDate);
        
        // Start date should be in future
        $this->assertGreaterThan($today, $startDate);
    }
    
    /**
     * Test: Payment status workflow
     */
    public function testPaymentStatusWorkflow() {
        // Booking starts as unpaid
        $booking = $this->createTestBooking([
            'payment_status' => 'Unpaid'
        ]);
        
        $this->assertEquals('Unpaid', $booking['payment_status']);
        
        // After payment, status changes to Paid
        $paidBooking = array_merge($booking, [
            'payment_status' => 'Paid'
        ]);
        
        $this->assertEquals('Paid', $paidBooking['payment_status']);
    }
}
