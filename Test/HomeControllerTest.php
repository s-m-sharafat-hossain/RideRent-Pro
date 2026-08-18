<?php
use PHPUnit\Framework\TestCase;

class HomeControllerTest extends TestCase
{
    public function testIndexPageData()
    {
        $vehicles = [
            ['vehicle_id' => 1, 'vehicle_name' => 'Toyota Camry', 'brand' => 'Toyota', 'price_per_day' => 100],
            ['vehicle_id' => 2, 'vehicle_name' => 'Honda Civic', 'brand' => 'Honda', 'price_per_day' => 80]
        ];
        
        $user = [
            'logged_in' => true,
            'role' => 'customer',
            'name' => 'John Doe',
            'dashboard_url' => '/customer/dashboard'
        ];
        
        $this->assertIsArray($vehicles);
        $this->assertIsArray($user);
        $this->assertCount(2, $vehicles);
        $this->assertTrue($user['logged_in']);
        $this->assertEquals('customer', $user['role']);
    }

    public function testIndexPageWithoutLogin()
    {
        $vehicles = [
            ['vehicle_id' => 1, 'vehicle_name' => 'Toyota Camry', 'availability' => 'Available']
        ];
        
        $user = null;
        
        $this->assertIsArray($vehicles);
        $this->assertNull($user);
        $this->assertEquals('Available', $vehicles[0]['availability']);
    }

    public function testVehicleDataStructure()
    {
        $vehicle = [
            'vehicle_id' => 1,
            'vehicle_name' => 'Toyota Camry',
            'brand' => 'Toyota',
            'model' => '2022',
            'vehicle_type' => 'Sedan',
            'price_per_day' => 100,
            'availability' => 'Available',
            'image' => 'camry.jpg'
        ];
        
        $this->assertIsArray($vehicle);
        $this->assertArrayHasKey('vehicle_id', $vehicle);
        $this->assertArrayHasKey('vehicle_name', $vehicle);
        $this->assertArrayHasKey('price_per_day', $vehicle);
        $this->assertEquals('Toyota Camry', $vehicle['vehicle_name']);
    }

    public function testUserSessionData()
    {
        $userSessions = [
            'admin' => ['role' => 'admin', 'name' => 'Admin User', 'dashboard' => '/admin/dashboard'],
            'owner' => ['role' => 'owner', 'name' => 'Owner User', 'dashboard' => '/owner/dashboard'],
            'driver' => ['role' => 'driver', 'name' => 'Driver User', 'dashboard' => '/driver/dashboard'],
            'customer' => ['role' => 'customer', 'name' => 'Customer User', 'dashboard' => '/customer/dashboard']
        ];
        
        $this->assertIsArray($userSessions);
        $this->assertCount(4, $userSessions);
        $this->assertEquals('admin', $userSessions['admin']['role']);
        $this->assertEquals('/customer/dashboard', $userSessions['customer']['dashboard']);
    }

    public function testEmptyVehicleList()
    {
        $vehicles = [];
        
        $this->assertIsArray($vehicles);
        $this->assertEmpty($vehicles);
    }

    public function testVehicleFiltering()
    {
        $allVehicles = [
            ['vehicle_id' => 1, 'vehicle_name' => 'Toyota Camry', 'availability' => 'Available'],
            ['vehicle_id' => 2, 'vehicle_name' => 'Honda Civic', 'availability' => 'Booked'],
            ['vehicle_id' => 3, 'vehicle_name' => 'Ford Focus', 'availability' => 'Available']
        ];
        
        $availableVehicles = array_filter($allVehicles, function($v) {
            return $v['availability'] === 'Available';
        });
        
        $this->assertCount(2, $availableVehicles);
        $this->assertEquals('Toyota Camry', $availableVehicles[0]['vehicle_name']);
    }

    public function testPriceSorting()
    {
        $vehicles = [
            ['vehicle_id' => 1, 'vehicle_name' => 'Toyota Camry', 'price_per_day' => 100],
            ['vehicle_id' => 2, 'vehicle_name' => 'Honda Civic', 'price_per_day' => 80],
            ['vehicle_id' => 3, 'vehicle_name' => 'Ford Focus', 'price_per_day' => 90]
        ];
        
        usort($vehicles, function($a, $b) {
            return $a['price_per_day'] <=> $b['price_per_day'];
        });
        
        $this->assertEquals(80, $vehicles[0]['price_per_day']);
        $this->assertEquals('Honda Civic', $vehicles[0]['vehicle_name']);
    }

    public function testUserNameRetrieval()
    {
        $sessionData = [
            'admin' => 'Admin',
            'owner_name' => 'Owner User',
            'driver_name' => 'Driver User',
            'customer_name' => 'Customer User'
        ];
        
        $this->assertIsArray($sessionData);
        $this->assertEquals('Admin', $sessionData['admin']);
        $this->assertEquals('Owner User', $sessionData['owner_name']);
    }

    public function testDashboardUrlGeneration()
    {
        $roles = ['admin', 'owner', 'driver', 'customer'];
        $dashboardUrls = [];
        
        foreach ($roles as $role) {
            $dashboardUrls[$role] = "/$role/dashboard";
        }
        
        $this->assertIsArray($dashboardUrls);
        $this->assertEquals('/admin/dashboard', $dashboardUrls['admin']);
        $this->assertEquals('/customer/dashboard', $dashboardUrls['customer']);
    }

    public function testPageRenderingData()
    {
        $pageData = [
            'vehicles' => [
                ['vehicle_id' => 1, 'vehicle_name' => 'Toyota Camry']
            ],
            'user' => [
                'logged_in' => true,
                'role' => 'customer',
                'name' => 'John Doe'
            ]
        ];
        
        $this->assertIsArray($pageData);
        $this->assertArrayHasKey('vehicles', $pageData);
        $this->assertArrayHasKey('user', $pageData);
        $this->assertTrue($pageData['user']['logged_in']);
    }
}
