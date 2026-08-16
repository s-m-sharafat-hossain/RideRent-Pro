<?php
/**
 * Driver Availability View
 * 
 * This view displays the availability management interface for drivers.
 * It allows drivers to update their availability status for accepting new bookings.
 * 
 * @package RideRentPro\Views\Driver
 * @author RideRent Pro Team
 * @version 1.0.0
 * 
 * @var array $driver Driver information including current availability status
 * @var string $userName The name of the current driver
 */
$pageTitle = 'My Availability';
require_once __DIR__ . '/../layouts/main.php';
?>

<!-- Dashboard Container -->
<div class="dashboard-container">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-clock"></i> My Availability</h1>
            <p>Update your availability status</p>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Availability Status</h3>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="form-group">
                        <label class="form-label">Current Status</label>
                        <select name="availability_status" class="form-select" required>
                            <option value="Available" <?= $driver['availability'] == 'Available' ? 'selected' : ''; ?>>Available</option>
                            <option value="Unavailable" <?= $driver['availability'] == 'Unavailable' ? 'selected' : ''; ?>>Unavailable</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Availability</button>
                </form>
            </div>
        </div>
    </div>
</div>
