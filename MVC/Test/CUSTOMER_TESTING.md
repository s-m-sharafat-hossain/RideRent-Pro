# Testing Customer Module Only - Quick Reference

## 📁 Customer Module Test File Location
**File:** `tests/Feature/CustomerModuleTest.php`

This file contains **31 focused tests** exclusively for the customer module.

---

## 🚀 Quick Commands

### Run ONLY Customer Module Tests
```powershell
vendor\bin\phpunit tests/Feature/CustomerModuleTest.php
```

### Run Customer Tests with Verbose Output
```powershell
vendor\bin\phpunit tests/Feature/CustomerModuleTest.php --verbose
```

### Run Customer Tests with Coverage Report
```powershell
vendor\bin\phpunit tests/Feature/CustomerModuleTest.php --coverage-html coverage
```

### Run Specific Customer Test
```powershell
vendor\bin\phpunit --filter testCustomerCanCreateBooking tests/Feature/CustomerModuleTest.php
```

---

## ✅ Test Categories in CustomerModuleTest

### 1. Dashboard Tests (4 tests)
- ✓ Total bookings calculation
- ✓ Active bookings calculation
- ✓ Total spending calculation
- ✓ Session data accessibility

### 2. Vehicle Browsing Tests (5 tests)
- ✓ Browse all vehicles
- ✓ Search vehicles by name
- ✓ Filter vehicles by price range
- ✓ View vehicle details
- ✓ Compare vehicles

### 3. Booking Tests (9 tests)
- ✓ Create booking
- ✓ Booking requires vehicle
- ✓ Booking requires valid dates
- ✓ View customer bookings
- ✓ Payment status starts unpaid
- ✓ Calculate booking total price
- ✓ Cancel pending booking
- ✓ Booking status progression
- ✓ Vehicle comparison with pricing

### 4. Review & Rating Tests (6 tests)
- ✓ Submit review after booking
- ✓ Rating validation (1-5 stars)
- ✓ Optional comment field
- ✓ Rating is required
- ✓ View customer reviews
- ✓ Filter by customer

### 5. Payment Tests (4 tests)
- ✓ Make payment for booking
- ✓ Payment amount matches booking total
- ✓ Booking requires payment
- ✓ Payment status after transaction

### 6. Profile Tests (4 tests)
- ✓ View profile
- ✓ Update profile
- ✓ Profile has contact info
- ✓ Email is unique identifier

### 7. Comparison Tests (2 tests)
- ✓ Compare multiple vehicles
- ✓ Price difference calculation

**Total: 31 tests**

---

## 📊 Test Output Example

```powershell
vendor\bin\phpunit tests/Feature/CustomerModuleTest.php

PHPUnit 10.0.0 by Sebastian Bergmann and contributors.

...............................                              31 / 31 (100%)

Time: 00:00.156s
Memory: 4.50 MB

OK (31 tests, 62 assertions)
```

---

## 🎯 What Gets Tested

### Customer Dashboard
- Total bookings count
- Active bookings (Pending & Confirmed only)
- Total spending (Paid bookings only)

### Vehicle Browsing
- Browse available vehicles
- Search by vehicle name
- Filter by price range
- View detailed vehicle info

### Booking Management
- Create new bookings
- View booking history
- Booking status changes
- Date validation
- Price calculation

### Reviews & Ratings
- Submit reviews for completed bookings
- Rating must be 1-5 stars
- Optional comments
- View review history

### Payment Processing
- Record payments
- Payment status tracking
- Amount validation

### Customer Profile
- View profile info
- Update profile details
- Manage contact information

### Vehicle Comparison
- Compare multiple vehicles side-by-side
- Price comparison

---

## 💡 Common Use Cases

### Test Dashboard Calculations
```powershell
vendor\bin\phpunit --filter "Dashboard" tests/Feature/CustomerModuleTest.php
```

### Test Booking Functionality
```powershell
vendor\bin\phpunit --filter "Booking" tests/Feature/CustomerModuleTest.php
```

### Test Review & Rating
```powershell
vendor\bin\phpunit --filter "Review" tests/Feature/CustomerModuleTest.php
```

### Test Payment Processing
```powershell
vendor\bin\phpunit --filter "Payment" tests/Feature/CustomerModuleTest.php
```

### Test Vehicle Browsing
```powershell
vendor\bin\phpunit --filter "Browse|Search|Filter" tests/Feature/CustomerModuleTest.php
```

### Test Profile Management
```powershell
vendor\bin\phpunit --filter "Profile" tests/Feature/CustomerModuleTest.php
```

---

## 🔍 Viewing Test Details

### Show Test Names Only (No Output)
```powershell
vendor\bin\phpunit --list-tests tests/Feature/CustomerModuleTest.php
```

### Run Tests with All Assertions Visible
```powershell
vendor\bin\phpunit tests/Feature/CustomerModuleTest.php -v
```

### Stop on First Failure
```powershell
vendor\bin\phpunit tests/Feature/CustomerModuleTest.php -x
```

### Run Until N Failures
```powershell
vendor\bin\phpunit tests/Feature/CustomerModuleTest.php --fail-on-warning
```

---

## 📝 Test Data Helpers

All tests use these helper methods from `TestCase` class:

```php
// Create test customer
$customer = $this->createTestCustomer(['full_name' => 'Jane']);

// Create test booking
$booking = $this->createTestBooking(['customer_id' => 1]);

// Create test vehicle
$vehicle = $this->createTestVehicle(['price_per_day' => 150]);

// Create test review
$review = $this->createTestReview(['rating' => 5]);

// Create test driver
$driver = $this->createTestDriver(['full_name' => 'John']);
```

---

## ⚡ Performance Tips

### Run Only Failed Tests from Last Run
```powershell
vendor\bin\phpunit tests/Feature/CustomerModuleTest.php --re-run-failed
```

### Run Tests in Parallel (Faster)
```powershell
vendor\bin\phpunit tests/Feature/CustomerModuleTest.php --process-isolation
```

---

## 🐛 Debugging

### Show Full Error Messages
```powershell
vendor\bin\phpunit tests/Feature/CustomerModuleTest.php --verbose
```

### Stop After First Failure
```powershell
vendor\bin\phpunit tests/Feature/CustomerModuleTest.php -x
```

### Show Skipped Tests
```powershell
vendor\bin\phpunit tests/Feature/CustomerModuleTest.php -v
```

---

## ✨ Next Steps

1. **Run the tests:**
   ```powershell
   vendor\bin\phpunit tests/Feature/CustomerModuleTest.php
   ```

2. **Check coverage:**
   ```powershell
   vendor\bin\phpunit tests/Feature/CustomerModuleTest.php --coverage-html coverage
   ```

3. **Add more tests** as you discover edge cases

4. **Document failures** for debugging

---

## 📚 Related Files

- **Main guide:** `UNIT_TESTING_GUIDE.md`
- **Quick start:** `QUICK_START_TESTING.md`
- **Bootstrap:** `tests/bootstrap.php`
- **Base test class:** `tests/TestCase.php`

---

## Summary

✅ **31 tests** covering all customer module functionality  
✅ **Run anytime:** `vendor\bin\phpunit tests/Feature/CustomerModuleTest.php`  
✅ **Focused testing** - Only customer module, no other modules  
✅ **Well-organized** - Grouped by feature area  
