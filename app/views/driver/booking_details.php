<?php
/**
 * Driver Booking Details View
 * 
 * This view displays detailed information about a specific booking.
 * It shows complete booking details including customer, vehicle, and route information.
 * 
 * @package RideRentPro\Views\Driver
 * @author RideRent Pro Team
 * @version 1.0.0
 * 
 * @var array $booking Detailed booking information with all related data
 * @var string $userName The name of the current driver
 */
$pageTitle = 'Booking Details';
require_once __DIR__ . '/../layouts/main.php';
?>

<!-- Dashboard Container -->
<div class="dashboard-container">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-file-alt"></i> Booking Details</h1>
            <p>View detailed booking information</p>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Booking #<?= htmlspecialchars($booking['booking_id']); ?></h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                    <div>
                        <h4>Customer Information</h4>
                        <table style="width: 100%; margin-top: 15px;">
                            <tr><th style="padding: 10px; background: var(--off-white);">Name:</th><td style="padding: 10px;"><?= htmlspecialchars($booking['customer_name']); ?></td></tr>
                            <tr><th style="padding: 10px; background: var(--off-white);">Email:</th><td style="padding: 10px;"><?= htmlspecialchars($booking['customer_email']); ?></td></tr>
                            <tr><th style="padding: 10px; background: var(--off-white);">Phone:</th><td style="padding: 10px;"><?= htmlspecialchars($booking['customer_phone']); ?></td></tr>
                        </table>
                    </div>
                    <div>
                        <h4>Vehicle Information</h4>
                        <table style="width: 100%; margin-top: 15px;">
                            <tr><th style="padding: 10px; background: var(--off-white);">Vehicle:</th><td style="padding: 10px;"><?= htmlspecialchars($booking['vehicle_name']); ?></td></tr>
                            <tr><th style="padding: 10px; background: var(--off-white);">Type:</th><td style="padding: 10px;"><?= htmlspecialchars($booking['vehicle_type']); ?></td></tr>
                            <tr><th style="padding: 10px; background: var(--off-white);">Daily Rate:</th><td style="padding: 10px;"><?= htmlspecialchars($booking['daily_rate']); ?></td></tr>
                        </table>
                    </div>
                </div>
                
                <hr style="margin: 30px 0;">
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                    <div>
                        <h4>Booking Details</h4>
                        <table style="width: 100%; margin-top: 15px;">
                            <tr><th style="padding: 10px; background: var(--off-white);">Start Date:</th><td style="padding: 10px;"><?= htmlspecialchars($booking['start_date']); ?></td></tr>
                            <tr><th style="padding: 10px; background: var(--off-white);">End Date:</th><td style="padding: 10px;"><?= htmlspecialchars($booking['end_date']); ?></td></tr>
                            <tr><th style="padding: 10px; background: var(--off-white);">Total Days:</th><td style="padding: 10px;"><?= htmlspecialchars($booking['total_days']); ?></td></tr>
                            <tr><th style="padding: 10px; background: var(--off-white);">Total Price:</th><td style="padding: 10px;"><?= htmlspecialchars($booking['total_price']); ?></td></tr>
                            <tr><th style="padding: 10px; background: var(--off-white);">Driver Fee:</th><td style="padding: 10px;"><?= htmlspecialchars($booking['driver_fee']); ?></td></tr>
                        </table>
                    </div>
                    <div>
                        <h4>Status Information</h4>
                        <table style="width: 100%; margin-top: 15px;">
                            <tr><th style="padding: 10px; background: var(--off-white);">Booking Status:</th><td style="padding: 10px;"><?= htmlspecialchars($booking['booking_status']); ?></td></tr>
                            <tr><th style="padding: 10px; background: var(--off-white);">Payment Status:</th><td style="padding: 10px;"><?= htmlspecialchars($booking['payment_status']); ?></td></tr>
                            <tr><th style="padding: 10px; background: var(--off-white);">Pickup Location:</th><td style="padding: 10px;"><?= htmlspecialchars($booking['pickup_location']); ?></td></tr>
                            <tr><th style="padding: 10px; background: var(--off-white);">Dropoff Location:</th><td style="padding: 10px;"><?= htmlspecialchars($booking['dropoff_location']); ?></td></tr>
                        </table>
                    </div>
                </div>
                
                <div style="margin-top: 30px;">
                    <a href="<?= APP_BASE_URL ?>/driver/bookings" class="btn btn-secondary">Back to Bookings</a>
                </div>
            </div>
        </div>
    </div>
</div>
