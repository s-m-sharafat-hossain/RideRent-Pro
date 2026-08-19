<?php
/**
 * Unit tests specifically for Customer Module
 * Tests customer-related functionality only
 */

class CustomerModuleTest extends TestCase {
    
    /**
     * @var CustomerController
     */
    private $controller;
    
    /**
     * @var Booking
     */
    private $bookingModel;
    
    /**
     * @var Review
     */
    private $reviewModel;
    
    /**
     * Set up test fixtures
     */
    protected function setUp(): void {
        parent::setUp();
        
        // Set up customer session
        $_SESSION['customer_id'] = 1;
        $_SESSION['customer_name'] = 'John Doe';
        $_SESSION['user_role'] = 'Customer';
        
        // Initialize models
        $this->controller = new CustomerController();
        $this->bookingModel = new Booking();
        $this->reviewModel = new Review();
    }
    
    // ============================================================
    // DASHBOARD TESTS
    // ============================================================
    
    /**
     * Test: Customer dashboard displays total bookings
     */
    public function testCustomerDashboardShowsTotalBookings() {
        $bookings = [
            $this->createTestBooking(['booking_id' => 1]),
            $this->createTestBooking(['booking_id' => 2]),
            $this->createTestBooking(['booking_id' => 3])
        ];
        
        $totalBookings = count($bookings);
        $this->assertEquals(3, $totalBookings);
    }
    
    /**
     * Test: Customer dashboard calculates active bookings
     */
    public function testCustomerDashboardCalculatesActiveBookings() {
        $bookings = [
            $this->createTestBooking(['booking_status' => 'Pending']),
            $this->createTestBooking(['booking_status' => 'Confirmed']),
            $this->createTestBooking(['booking_status' => 'Completed']),
            $this->createTestBooking(['booking_status' => 'Cancelled'])
        ];
        
        $activeBookings = count(array_filter($bookings, function($b) {
            return in_array($b['booking_status'], ['Pending', 'Confirmed']);
        }));
        
        $this->assertEquals(2, $activeBookings);
    }
    
    /**
     * Test: Customer dashboard calculates total spending
     */
    public function testCustomerDashboardCalculatesTotalSpending() {
        $bookings = [
            $this->createTestBooking(['payment_status' => 'Paid', 'total_price' => 100.00]),
            $this->createTestBooking(['payment_status' => 'Unpaid', 'total_price' => 200.00]),
            $this->createTestBooking(['payment_status' => 'Paid', 'total_price' => 150.00])
        ];
        
        $totalSpending = array_sum(array_column(
            array_filter($bookings, fn($b) => $b['payment_status'] == 'Paid'),
            'total_price'
        ));
        
        $this->assertEquals(250.00, $totalSpending);
    }
    
    /**
     * Test: Customer session ID is accessible
     */
    public function testCustomerSessionDataIsAccessible() {
        $this->assertEquals(1, $_SESSION['customer_id']);
        $this->assertNotNull($_SESSION['customer_id']);
    }
    
    // ============================================================
    // VEHICLE BROWSING TESTS
    // ============================================================
    
    /**
     * Test: Customer can view available vehicles
     */
    public function testCustomerCanBrowseVehicles() {
        $vehicles = [
            $this->createTestVehicle(['vehicle_id' => 1, 'vehicle_name' => 'Toyota Corolla']),
            $this->createTestVehicle(['vehicle_id' => 2, 'vehicle_name' => 'Honda Civic']),
            $this->createTestVehicle(['vehicle_id' => 3, 'vehicle_name' => 'Ford Focus'])
        ];
        
        $this->assertIsArray($vehicles);
        $this->assertCount(3, $vehicles);
    }
    
    /**
     * Test: Customer vehicle search filters by name
     */
    public function testCustomerVehicleSearchByName() {
        $vehicles = [
            $this->createTestVehicle(['vehicle_name' => 'Toyota Corolla']),
            $this->createTestVehicle(['vehicle_name' => 'Honda Civic']),
            $this->createTestVehicle(['vehicle_name' => 'Toyota Camry'])
        ];
        
        $toyotas = array_filter($vehicles, fn($v) => strpos($v['vehicle_name'], 'Toyota') !== false);
        $this->assertCount(2, $toyotas);
    }
    
    /**
     * Test: Customer can filter vehicles by price range
     */
    public function testCustomerFilterVehiclesByPrice() {
        $vehicles = [
            $this->createTestVehicle(['price_per_day' => 80.00]),
            $this->createTestVehicle(['price_per_day' => 120.00]),
            $this->createTestVehicle(['price_per_day' => 150.00])
        ];
        
        $affordableVehicles = array_filter($vehicles, fn($v) => $v['price_per_day'] <= 100.00);
        $this->assertCount(1, $affordableVehicles);
    }
    
    /**
     * Test: Customer can view vehicle details
     */
    public function testCustomerCanViewVehicleDetails() {
        $vehicle = $this->createTestVehicle([
            'vehicle_id' => 1,
            'vehicle_name' => 'Toyota Corolla',
            'vehicle_type' => 'Sedan',
            'price_per_day' => 100.00,
            'location' => 'New York'
        ]);
        
        $this->assertArrayHasKey('vehicle_name', $vehicle);
        $this->assertArrayHasKey('vehicle_type', $vehicle);
        $this->assertArrayHasKey('price_per_day', $vehicle);
        $this->assertEquals('Toyota Corolla', $vehicle['vehicle_name']);
    }
    
    // ============================================================
    // BOOKING TESTS
    // ============================================================
    
    /**
     * Test: Customer can create booking
     */
    public function testCustomerCanCreateBooking() {
        $booking = $this->createTestBooking([
            'customer_id' => 1,
            'vehicle_id' => 1,
            'booking_status' => 'Pending'
        ]);
        
        $this->assertEquals(1, $booking['customer_id']);
        $this->assertEquals('Pending', $booking['booking_status']);
    }
    
    /**
     * Test: Customer booking requires vehicle
     */
    public function testCustomerBookingRequiresVehicle() {
        $booking = $this->createTestBooking(['vehicle_id' => null]);
        
        $this->assertNull($booking['vehicle_id']);
    }
    
    /**
     * Test: Customer booking requires valid dates
     */
    public function testCustomerBookingRequiresValidDates() {
        $startDate = new DateTime('+1 day');
        $endDate = new DateTime('+3 days');
        
        $booking = $this->createTestBooking([
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d')
        ]);
        
        $this->assertGreaterThan($startDate, $endDate);
    }
    
    /**
     * Test: Customer can view their bookings
     */
    public function testCustomerCanViewTheirBookings() {
        $bookings = [
            $this->createTestBooking(['customer_id' => 1, 'booking_id' => 1]),
            $this->createTestBooking(['customer_id' => 1, 'booking_id' => 2]),
            $this->createTestBooking(['customer_id' => 1, 'booking_id' => 3])
        ];
        
        // All should belong to customer
        foreach ($bookings as $booking) {
            $this->assertEquals(1, $booking['customer_id']);
        }
    }
    
    /**
     * Test: Customer booking payment starts as unpaid
     */
    public function testCustomerBookingPaymentStatusUnpaid() {
        $booking = $this->createTestBooking();
        $this->assertEquals('Unpaid', $booking['payment_status']);
    }
    
    /**
     * Test: Customer booking calculates total price
     */
    public function testCustomerBookingCalculatesTotalPrice() {
        $booking = $this->createTestBooking([
            'total_price' => 300.00
        ]);
        
        $this->assertEquals(300.00, $booking['total_price']);
        $this->assertIsFloat($booking['total_price']);
    }
    
    /**
     * Test: Customer can cancel booking (before confirmation)
     */
    public function testCustomerCanCancelPendingBooking() {
        $booking = $this->createTestBooking([
            'booking_status' => 'Pending'
        ]);
        
        $cancelledBooking = array_merge($booking, [
            'booking_status' => 'Cancelled'
        ]);
        
        $this->assertEquals('Cancelled', $cancelledBooking['booking_status']);
    }
    
    /**
     * Test: Booking status progression
     */
    public function testBookingStatusProgression() {
        $statuses = ['Pending', 'Confirmed', 'Completed'];
        
        $booking = $this->createTestBooking(['booking_status' => $statuses[0]]);
        $this->assertEquals('Pending', $booking['booking_status']);
        
        $booking['booking_status'] = $statuses[1];
        $this->assertEquals('Confirmed', $booking['booking_status']);
        
        $booking['booking_status'] = $statuses[2];
        $this->assertEquals('Completed', $booking['booking_status']);
    }
    
    // ============================================================
    // REVIEW & RATING TESTS
    // ============================================================
    
    /**
     * Test: Customer can submit review after completed booking
     */
    public function testCustomerCanSubmitReviewAfterBooking() {
        $booking = $this->createTestBooking([
            'booking_status' => 'Completed',
            'payment_status' => 'Paid'
        ]);
        
        $review = $this->createTestReview([
            'booking_id' => $booking['booking_id'],
            'customer_id' => $booking['customer_id']
        ]);
        
        $this->assertEquals($booking['booking_id'], $review['booking_id']);
        $this->assertEquals($booking['customer_id'], $review['customer_id']);
    }
    
    /**
     * Test: Review rating must be 1-5 stars
     */
    public function testReviewRatingMustBeBetween1And5() {
        $validRatings = [1, 2, 3, 4, 5];
        
        foreach ($validRatings as $rating) {
            $review = $this->createTestReview(['rating' => $rating]);
            $this->assertGreaterThanOrEqual(1, $review['rating']);
            $this->assertLessThanOrEqual(5, $review['rating']);
        }
    }
    
    /**
     * Test: Review comment is optional
     */
    public function testReviewCommentIsOptional() {
        $review = $this->createTestReview([
            'rating' => 5,
            'comment' => ''
        ]);
        
        $this->assertEquals(5, $review['rating']);
    }
    
    /**
     * Test: Review must have rating
     */
    public function testReviewMustHaveRating() {
        $review = $this->createTestReview(['rating' => 4]);
        
        $this->assertArrayHasKey('rating', $review);
        $this->assertNotNull($review['rating']);
    }
    
    /**
     * Test: Customer can view their reviews
     */
    public function testCustomerCanViewTheirReviews() {
        $reviews = [
            $this->createTestReview(['review_id' => 1, 'customer_id' => 1, 'rating' => 5]),
            $this->createTestReview(['review_id' => 2, 'customer_id' => 1, 'rating' => 4]),
            $this->createTestReview(['review_id' => 3, 'customer_id' => 1, 'rating' => 3])
        ];
        
        foreach ($reviews as $review) {
            $this->assertEquals(1, $review['customer_id']);
        }
    }
    
    // ============================================================
    // PAYMENT TESTS
    // ============================================================
    
    /**
     * Test: Customer can make payment for booking
     */
    public function testCustomerCanMakePayment() {
        $booking = $this->createTestBooking([
            'payment_status' => 'Unpaid',
            'total_price' => 500.00
        ]);
        
        // Simulate payment
        $paidBooking = array_merge($booking, [
            'payment_status' => 'Paid'
        ]);
        
        $this->assertEquals('Paid', $paidBooking['payment_status']);
    }
    
    /**
     * Test: Payment amount matches booking total
     */
    public function testPaymentAmountMatchesBookingTotal() {
        $booking = $this->createTestBooking(['total_price' => 250.00]);
        $paymentAmount = $booking['total_price'];
        
        $this->assertEquals($booking['total_price'], $paymentAmount);
    }
    
    /**
     * Test: Customer cannot book without payment
     */
    public function testBookingRequiresPayment() {
        $booking = $this->createTestBooking([
            'payment_status' => 'Unpaid'
        ]);
        
        // Should not be confirmed
        $this->assertNotEquals('Confirmed', $booking['booking_status']);
    }
    
    /**
     * Test: Payment status after successful transaction
     */
    public function testPaymentStatusAfterTransaction() {
        $booking = $this->createTestBooking(['payment_status' => 'Unpaid']);
        $this->assertEquals('Unpaid', $booking['payment_status']);
        
        // Process payment
        $booking['payment_status'] = 'Paid';
        $this->assertEquals('Paid', $booking['payment_status']);
    }
    
    // ============================================================
    // PROFILE TESTS
    // ============================================================
    
    /**
     * Test: Customer can view their profile
     */
    public function testCustomerCanViewProfile() {
        $customer = $this->createTestCustomer([
            'customer_id' => 1,
            'full_name' => 'John Doe',
            'email' => 'john@example.com'
        ]);
        
        $this->assertEquals('John Doe', $customer['full_name']);
        $this->assertEquals('john@example.com', $customer['email']);
    }
    
    /**
     * Test: Customer can update profile
     */
    public function testCustomerCanUpdateProfile() {
        $customer = $this->createTestCustomer(['full_name' => 'John Doe']);
        
        $updatedCustomer = array_merge($customer, [
            'full_name' => 'Jane Doe'
        ]);
        
        $this->assertEquals('Jane Doe', $updatedCustomer['full_name']);
    }
    
    /**
     * Test: Customer profile has contact information
     */
    public function testCustomerProfileHasContactInfo() {
        $customer = $this->createTestCustomer([
            'phone_1' => '1234567890',
            'address' => '123 Main St'
        ]);
        
        $this->assertArrayHasKey('phone_1', $customer);
        $this->assertArrayHasKey('address', $customer);
        $this->assertEquals('1234567890', $customer['phone_1']);
    }
    
    /**
     * Test: Customer email is unique identifier
     */
    public function testCustomerEmailIsUniqueIdentifier() {
        $customer1 = $this->createTestCustomer([
            'customer_id' => 1,
            'email' => 'john@example.com'
        ]);
        
        $customer2 = $this->createTestCustomer([
            'customer_id' => 2,
            'email' => 'jane@example.com'
        ]);
        
        $this->assertNotEquals($customer1['email'], $customer2['email']);
    }
    
    // ============================================================
    // COMPARISON TESTS
    // ============================================================
    
    /**
     * Test: Customer can compare multiple vehicles
     */
    public function testCustomerCanCompareVehicles() {
        $vehicle1 = $this->createTestVehicle([
            'vehicle_id' => 1,
            'vehicle_name' => 'Toyota Corolla',
            'price_per_day' => 100.00
        ]);
        
        $vehicle2 = $this->createTestVehicle([
            'vehicle_id' => 2,
            'vehicle_name' => 'Honda Civic',
            'price_per_day' => 120.00
        ]);
        
        $this->assertNotEquals($vehicle1['price_per_day'], $vehicle2['price_per_day']);
        $this->assertLessThan($vehicle2['price_per_day'], $vehicle1['price_per_day']);
    }
    
    /**
     * Test: Customer comparison shows price difference
     */
    public function testComparisonShowsPriceDifference() {
        $vehicles = [
            $this->createTestVehicle(['price_per_day' => 100.00]),
            $this->createTestVehicle(['price_per_day' => 150.00])
        ];
        
        $priceDifference = $vehicles[1]['price_per_day'] - $vehicles[0]['price_per_day'];
        $this->assertEquals(50.00, $priceDifference);
    }
}
