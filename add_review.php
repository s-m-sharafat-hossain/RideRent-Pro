<?php
/**
 * Customer Add Review View
 * 
 * This view displays the review submission form for customers.
 * It allows customers to rate and review vehicles they have booked.
 * 
 * @package RideRentPro\Views\Customer
 * @author RideRent Pro Team
 * @version 1.0.0
 * 
 * @var array $booking Booking information for the vehicle being reviewed
 * @var string $userName The name of the current customer
 */
$pageTitle = 'Add Review';
require_once __DIR__ . '/../layouts/main.php';
?>

<!-- Dashboard Container -->
<div class="dashboard-container">
    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-star"></i> Add Review</h1>
            <p>Share your experience</p>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="POST">
                    <div class="form-group">
                        <label class="form-label">Rating (1-5)</label>
                        <select name="rating" class="form-select" required>
                            <option value="">Select Rating</option>
                            <option value="5">5 - Excellent</option>
                            <option value="4">4 - Good</option>
                            <option value="3">3 - Average</option>
                            <option value="2">2 - Poor</option>
                            <option value="1">1 - Very Poor</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Comment</label>
                        <textarea name="comment" class="form-control" rows="4" required></textarea>
                    </div>
                    <input type="hidden" name="booking_id" value="<?= $booking['booking_id'] ?? ''; ?>">
                    <input type="hidden" name="vehicle_id" value="<?= $booking['vehicle_id'] ?? ''; ?>">
                    <input type="hidden" name="driver_id" value="<?= $booking['driver_id'] ?? ''; ?>">
                    <button type="submit" class="btn btn-primary">Submit Review</button>
                    <a href="/customer/bookings" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>