# Quick Start Guide - Unit Testing Customer Module

## TL;DR - Get Started in 3 Minutes

### Step 1: Install PHPUnit (1 minute)
```powershell
cd c:\xampp\htdocs\RideRentPro\RideRentPro
composer require --dev phpunit/phpunit:^10.0
```

### Step 2: Run All Tests (1 minute)
```powershell
vendor\bin\phpunit
```

### Step 3: View Results
You'll see test results in the console showing:
- ✓ Passed tests (green)
- ✗ Failed tests (red)
- Number of tests run
- Code coverage percentage

---

## Files Created For You

| File | Purpose |
|------|---------|
| `phpunit.xml` | PHPUnit configuration |
| `tests/bootstrap.php` | Test environment setup & autoloading |
| `tests/TestCase.php` | Base class with helper methods |
| `tests/Unit/UserModelTest.php` | User model tests |
| `tests/Unit/BookingModelTest.php` | Booking model tests |
| `tests/Feature/CustomerControllerTest.php` | Customer controller tests |
| `tests/Feature/CustomerWorkflowTest.php` | Integration workflow tests |

---

## Running Tests - Common Commands

### Run All Tests
```powershell
vendor\bin\phpunit
```

### Run Only Unit Tests
```powershell
vendor\bin\phpunit tests/Unit
```

### Run Only Feature Tests
```powershell
vendor\bin\phpunit tests/Feature
```

### Run Specific Test File
```powershell
vendor\bin\phpunit tests/Unit/UserModelTest.php
```

### Run Specific Test Method
```powershell
vendor\bin\phpunit --filter testAuthenticateWithValidCustomerCredentials
```

### Run with Code Coverage Report
```powershell
vendor\bin\phpunit --coverage-html coverage
# Opens HTML report in: coverage/index.html
```

### Run with Verbose Output
```powershell
vendor\bin\phpunit --verbose
```

---

## What Each Test File Tests

### UserModelTest.php (7 tests)
- ✓ Model instantiation
- ✓ Authentication with invalid email
- ✓ Valid role acceptance
- ✓ Invalid role rejection
- ✓ Email field validation
- ✓ Registration with valid role
- ✓ All role registration support

### BookingModelTest.php (8 tests)
- ✓ Model instantiation
- ✓ Get all bookings
- ✓ Get booking by ID structure
- ✓ Get bookings by customer
- ✓ Customer filter correctness
- ✓ Get bookings by owner
- ✓ Get bookings by driver
- ✓ Model inheritance and table name

### CustomerControllerTest.php (9 tests)
- ✓ Controller instantiation
- ✓ Controller inheritance
- ✓ Dashboard total bookings calculation
- ✓ Active bookings calculation
- ✓ Total spending calculation
- ✓ Spending excludes unpaid bookings
- ✓ Session data accessibility
- ✓ Model initialization
- ✓ Controller properties

### CustomerWorkflowTest.php (10 tests)
- ✓ Browse vehicles workflow
- ✓ Complete booking workflow
- ✓ Profile management
- ✓ Review submission
- ✓ Review rating validation
- ✓ Multiple bookings history
- ✓ Booking date validation
- ✓ Payment status workflow

**Total: 34 Tests**

---

## Understanding Test Results

### Success Output
```
vendor\bin\phpunit

PHPUnit 10.0.0 by Sebastian Bergmann and contributors.

...........................................           34 / 34 (100%)

Time: 00:00.234s
Memory: 5.00 MB

OK (34 tests, 45 assertions)
```

✅ All tests passed!

### Failed Test Output
```
vendor\bin\phpunit

PHPUnit 10.0.0 by Sebastian Bergmann and contributors.

F...........................................           33 / 34

FAIL (33 tests, 1 failure)
```

❌ 1 test failed - see which one in the output above

---

## Test Data Helpers

Use these in your test methods:

```php
// Create test customer
$customer = $this->createTestCustomer([
    'full_name' => 'Jane Doe',
    'email' => 'jane@example.com'
]);

// Create test booking
$booking = $this->createTestBooking([
    'customer_id' => 1,
    'booking_status' => 'Completed'
]);

// Create test vehicle
$vehicle = $this->createTestVehicle([
    'vehicle_name' => 'Tesla Model 3',
    'price_per_day' => 150
]);

// Create test review
$review = $this->createTestReview([
    'rating' => 5,
    'comment' => 'Great!'
]);

// Create test driver
$driver = $this->createTestDriver([
    'full_name' => 'John Driver'
]);
```

---

## Common Assertions

```php
// Equality
$this->assertEquals($expected, $actual);
$this->assertNotEquals($unexpected, $actual);

// Boolean
$this->assertTrue($condition);
$this->assertFalse($condition);

// Type checks
$this->assertIsArray($value);
$this->assertIsString($value);
$this->assertIsInt($value);
$this->assertNull($value);
$this->assertNotNull($value);

// Collection
$this->assertCount(5, $array);
$this->assertArrayHasKey('key', $array);

// Instance
$this->assertInstanceOf(ClassName::class, $object);

// String operations
$this->assertStringContains('substring', $string);
$this->assertStringStartsWith('prefix', $string);
```

---

## Troubleshooting

### Issue: "Cannot find PHPUnit"
**Solution:**
```powershell
composer install  # Make sure to run this first
```

### Issue: "Class not found" errors
**Solution:**
- Check `tests/bootstrap.php` has all required includes
- Verify class files exist in `app/` directory

### Issue: Database connection errors
**Solution:**
- Tests don't need actual database for unit tests
- Feature tests may need test database
- Use mock database for advanced tests

### Issue: Permission denied errors
**Solution:**
```powershell
# Run PowerShell as Administrator
```

---

## Next Steps

1. ✅ Run `vendor\bin\phpunit` to execute all tests
2. 📊 Check code coverage: `vendor\bin\phpunit --coverage-html coverage`
3. 🎯 Add more specific tests based on your use cases
4. 🔄 Run tests in CI/CD pipeline
5. 📈 Aim for >80% code coverage

---

## Need Help?

- **Documentation:** See `UNIT_TESTING_GUIDE.md` for complete guide
- **Test Examples:** Look inside test files for patterns
- **PHPUnit Docs:** https://phpunit.de/documentation.html

---

## Summary

✅ **Ready to test!** Run:
```powershell
vendor\bin\phpunit
```

You now have a complete testing setup with 34 pre-written tests covering the customer module!
