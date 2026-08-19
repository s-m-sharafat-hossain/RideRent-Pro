<?php
/**
 * Customer Payment View
 * 
 * This view displays the payment processing interface for customers.
 * It shows payment options and processing for completed bookings.
 * 
 * @package RideRentPro\Views\Customer
 * @author RideRent Pro Team
 * @version 1.0.0
 * 
 * @var int $bookingId The ID of the booking being paid for
 * @var string $userName The name of the current customer
 */
$pageTitle = 'Payment';
require_once __DIR__ . '/../layouts/main.php';
?>

<!-- Dashboard Container -->
<div class="dashboard-container">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-credit-card"></i> Payment</h1>
            <p>Complete your booking payment</p>
        </div>

        <div class="card">
            <div class="card-body">
                <p>Payment functionality coming soon. Booking ID: <?= $_GET['booking_id'] ?? 'N/A'; ?></p>
                <a href="<?= APP_BASE_URL ?>/customer/bookings" class="btn btn-secondary">Back to Bookings</a>
            </div>
        </div>
    </div>
</div>
