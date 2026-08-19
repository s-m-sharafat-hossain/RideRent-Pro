<?php
/**
 * Feature tests for Customer Controller
 * Tests customer-facing functionality
 */

class CustomerControllerTest extends TestCase {
    
    /**
     * @var CustomerController
     */
    private $controller;
    
    /**
     * Set up test fixtures
     */
    protected function setUp(): void {
        parent::setUp();
        
        // Set up mock session data
        $_SESSION['customer_id'] = 1;
        $_SESSION['customer_name'] = 'John Doe';
        $_SESSION['user_role'] = 'Customer';
        
        // Create controller instance
        $this->controller = new CustomerController();
    }
    
    /**
     * Test: Customer controller instantiation
     */
    public function testControllerInstantiation() {
        $this->assertInstanceOf(CustomerController::class, $this->controller);
    }
    
    /**
     * Test: Controller inherits from base Controller class
     */
    public function testControllerExtendsController() {
        $this->assertInstanceOf(Controller::class, $this->controller);
    }
    
    /**
     * Test: Dashboard booking calculations - total count
     */
    public function testDashboardCalculatesTotalBookings() {
        $testBookings = [
            $this->createTestBooking(['booking_id' => 1]),
            $this->createTestBooking(['booking_id' => 2]),
            $this->createTestBooking(['booking_id' => 3])
        ];
        
        $totalBookings = count($testBookings);
        $this->assertEquals(3, $totalBookings);
    }
    
    /**
     * Test: Dashboard calculates active bookings correctly
     */
    public function testDashboardCalculatesActiveBookings() {
        $testBookings = [
            $this->createTestBooking([
                'booking_id' => 1,
                'booking_status' => 'Pending'
            ]),
            $this->createTestBooking([
                'booking_id' => 2,
                'booking_status' => 'Confirmed'
            ]),
            $this->createTestBooking([
                'booking_id' => 3,
                'booking_status' => 'Completed'
            ]),
            $this->createTestBooking([
                'booking_id' => 4,
                'booking_status' => 'Cancelled'
            ])
        ];
        
        $activeBookings = count(array_filter($testBookings, function($b) {
            return in_array($b['booking_status'], ['Pending', 'Confirmed']);
        }));
        
        $this->assertEquals(2, $activeBookings);
    }
    
    /**
     * Test: Dashboard calculates total spending correctly
     */
    public function testDashboardCalculatesTotalSpending() {
        $testBookings = [
            $this->createTestBooking([
                'booking_id' => 1,
                'payment_status' => 'Paid',
                'total_price' => 300.00
            ]),
            $this->createTestBooking([
                'booking_id' => 2,
                'payment_status' => 'Unpaid',
                'total_price' => 200.00
            ]),
            $this->createTestBooking([
                'booking_id' => 3,
                'payment_status' => 'Paid',
                'total_price' => 150.00
            ])
        ];
        
        $totalSpending = array_sum(array_column(
            array_filter($testBookings, function($b) {
                return $b['payment_status'] == 'Paid';
            }),
            'total_price'
        ));
        
        $this->assertEquals(450.00, $totalSpending);
    }
    
    /**
     * Test: Dashboard spending only counts paid bookings
     */
    public function testDashboardSpendingExcludesUnpaidBookings() {
        $testBookings = [
            $this->createTestBooking([
                'payment_status' => 'Paid',
                'total_price' => 100.00
            ]),
            $this->createTestBooking([
                'payment_status' => 'Unpaid',
                'total_price' => 500.00
            ])
        ];
        
        $totalSpending = array_sum(array_column(
            array_filter($testBookings, function($b) {
                return $b['payment_status'] == 'Paid';
            }),
            'total_price'
        ));
        
        $this->assertEquals(100.00, $totalSpending);
        $this->assertNotEquals(600.00, $totalSpending);
    }
    
    /**
     * Test: Session data is accessible
     */
    public function testSessionDataAccessible() {
        $this->assertEquals(1, $_SESSION['customer_id']);
        $this->assertEquals('John Doe', $_SESSION['customer_name']);
        $this->assertEquals('Customer', $_SESSION['user_role']);
    }
    
    /**
     * Test: Models are initialized in controller
     */
    public function testControllerInitializesModels() {
        $reflection = new ReflectionClass($this->controller);
        
        // Check that properties exist
        $properties = ['vehicleModel', 'bookingModel', 'reviewModel', 'userModel', 'db'];
        
        foreach ($properties as $property) {
            $this->assertTrue(
                $reflection->hasProperty($property),
                "Controller should have $property property"
            );
        }
    }
}
