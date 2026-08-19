<?php
/**
 * Customer Book Vehicle View
 * 
 * This view displays the vehicle booking form for customers.
 * It allows customers to select dates, driver options, and locations for booking a vehicle.
 * 
 * @package RideRentPro\Views\Customer
 * @author RideRent Pro Team
 * @version 1.0.0
 * 
 * @var array $vehicle Vehicle information including details, pricing, and availability
 * @var string $userName The name of the current customer
 */
$pageTitle = 'Book Vehicle';
require_once __DIR__ . '/../layouts/main.php';
?>

<!-- Dashboard Container -->
<div class="dashboard-container">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-calendar-plus"></i> Book Vehicle</h1>
            <p>Complete your booking details</p>
        </div>

        <?php if(isset($error) && $error): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error['message']); ?>
            </div>
        <?php endif; ?>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
            <!-- Vehicle Details -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Vehicle Details</h3>
                </div>
                <div class="card-body">
                    <?php if(!empty($vehicle['image'])): ?>
                        <img src="/public/assets/uploads/<?= htmlspecialchars($vehicle['image']); ?>" alt="<?= htmlspecialchars($vehicle['vehicle_name']); ?>" style="width: 100%; height: 250px; object-fit: cover; border-radius: var(--radius-md); margin-bottom: 20px;">
                    <?php else: ?>
                        <img src="https://via.placeholder.com/400x250?text=No+Image" alt="<?= htmlspecialchars($vehicle['vehicle_name']); ?>" style="width: 100%; height: 250px; object-fit: cover; border-radius: var(--radius-md); margin-bottom: 20px;">
                    <?php endif; ?>
                    
                    <h2><?= htmlspecialchars($vehicle['vehicle_name']); ?></h2>
                    <p style="color: var(--medium-gray);"><?= htmlspecialchars($vehicle['brand']); ?> <?= htmlspecialchars($vehicle['model']); ?> (<?= htmlspecialchars($vehicle['year']); ?>)</p>
                    
                    <hr style="margin: 20px 0;">
                    
                    <table style="width: 100%;">
                        <tr><th style="padding: 10px; background: var(--off-white); color: var(--dark-gray); font-weight: 600;">Type:</th><td style="padding: 10px;"><?= htmlspecialchars($vehicle['vehicle_type']); ?></td></tr>
                        <tr><th style="padding: 10px; background: var(--off-white); color: var(--dark-gray); font-weight: 600;">Fuel:</th><td style="padding: 10px;"><?= htmlspecialchars($vehicle['fuel_type']); ?></td></tr>
                        <tr><th style="padding: 10px; background: var(--off-white); color: var(--dark-gray); font-weight: 600;">Transmission:</th><td style="padding: 10px;"><?= htmlspecialchars($vehicle['transmission']); ?></td></tr>
                        <tr><th style="padding: 10px; background: var(--off-white); color: var(--dark-gray); font-weight: 600;">Seats:</th><td style="padding: 10px;"><?= htmlspecialchars($vehicle['seat_capacity']); ?></td></tr>
                        <tr><th style="padding: 10px; background: var(--off-white); color: var(--dark-gray); font-weight: 600;">Location:</th><td style="padding: 10px;"><?= htmlspecialchars($vehicle['location']); ?></td></tr>
                    </table>
                    
                    <p style="font-size: 32px; font-weight: 700; color: var(--accent-pink); margin: 20px 0;">৳<?= htmlspecialchars($vehicle['price_per_day']); ?> / day</p>
                    
                    <?php if($vehicle['description']): ?>
                        <p><strong>Description:</strong><br><?= nl2br(htmlspecialchars($vehicle['description'])); ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Booking Form -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Booking Details</h3>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="form-group">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" required min="<?= date('Y-m-d'); ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" required min="<?= date('Y-m-d'); ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Driver Option</label>
                            <select name="driver_option" class="form-select" required>
                                <option value="without_driver">Without Driver</option>
                                <option value="with_driver">With Driver (+৳500/day)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Pickup Location</label>
                            <input type="text" name="pickup_location" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Dropoff Location</label>
                            <input type="text" name="dropoff_location" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Special Requests</label>
                            <textarea name="special_requests" class="form-control" rows="3"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Payment Option</label>
                            <div style="margin: 10px 0;">
                                <label style="margin-right: 20px;">
                                    <input type="radio" name="payment_option" value="pay_later" checked> Pay Later
                                </label>
                                <label>
                                    <input type="radio" name="payment_option" value="pay_now"> Pay Now
                                </label>
                            </div>
                            <small class="text-muted">Choose "Pay Now" to complete payment immediately, or "Pay Later" to pay separately.</small>
                        </div>
                        
                        <div style="background: var(--off-white); padding: 20px; border-radius: var(--radius-md); margin: 20px 0;">
                            <h4>Payment Summary</h4>
                            <p>Vehicle rental will be calculated based on your selected dates.</p>
                            <p><strong>Note:</strong> Driver fee (if selected) is ৳500 per day.</p>
                        </div>
                        
                        <button type="submit" name="book_vehicle" class="btn btn-primary" style="width: 100%;">Confirm Booking</button>
                        <a href="<?= APP_BASE_URL ?>/customer/vehicles" class="btn btn-secondary" style="width: 100%; margin-top: 10px;">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
