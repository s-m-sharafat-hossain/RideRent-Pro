<?php
/**
 * Customer Compare Vehicles View
 * 
 * This view displays the vehicle comparison interface for customers.
 * It allows customers to compare up to 3 vehicles side by side to make informed decisions.
 * 
 * @package RideRentPro\Views\Customer
 * @author RideRent Pro Team
 * @version 1.0.0
 * 
 * @var array $vehicles Array of vehicles currently in the comparison list
 * @var bool $hasTable Whether the compare_vehicles database table exists
 * @var string $userName The name of the current customer
 */
$pageTitle = 'Compare Vehicles';
require_once __DIR__ . '/../layouts/main.php';
?>

<!-- Dashboard Container -->
<div class="dashboard-container">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-balance-scale"></i> Compare Vehicles</h1>
            <p>Compare up to 3 vehicles side by side</p>
        </div>

        <?php if(empty($vehicles) && !$hasTable): ?>
            <div class="card">
                <div class="card-body">
                    <div style="text-align: center; padding: 40px;">
                        <h4>Compare feature is not available. Please contact administrator to set up the compare_vehicles table.</h4>
                    </div>
                </div>
            </div>
        <?php elseif(empty($vehicles)): ?>
            <div class="card">
                <div class="card-body">
                    <div style="text-align: center; padding: 40px;">
                        <h4>No vehicles to compare. <a href="<?= APP_BASE_URL ?>/customer/vehicles">Browse vehicles</a> and add them to compare!</h4>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="card">
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: repeat(<?= count($vehicles); ?>, 1fr); gap: 20px; overflow-x: auto;">
                        <?php foreach($vehicles as $vehicle): ?>
                            <div style="border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 20px; text-align: center;">
                                <?php if(!empty($vehicle['image'])): ?>
                                    <img src="/public/assets/uploads/<?= htmlspecialchars($vehicle['image']); ?>" alt="<?= htmlspecialchars($vehicle['vehicle_name']); ?>" style="width: 100%; height: 150px; object-fit: cover; border-radius: var(--radius-md); margin-bottom: 15px;">
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/300x150?text=No+Image" alt="<?= htmlspecialchars($vehicle['vehicle_name']); ?>" style="width: 100%; height: 150px; object-fit: cover; border-radius: var(--radius-md); margin-bottom: 15px;">
                                <?php endif; ?>
                                
                                <h3><?= htmlspecialchars($vehicle['vehicle_name']); ?></h3>
                                <p style="color: var(--accent-pink); font-size: 24px; font-weight: 700; margin: 10px 0;">৳<?= htmlspecialchars($vehicle['price_per_day']); ?>/day</p>
                                
                                <table style="width: 100%; margin: 15px 0; text-align: left;">
                                    <tr><td><strong>Brand:</strong></td><td><?= htmlspecialchars($vehicle['brand']); ?></td></tr>
                                    <tr><td><strong>Model:</strong></td><td><?= htmlspecialchars($vehicle['model']); ?></td></tr>
                                    <tr><td><strong>Year:</strong></td><td><?= htmlspecialchars($vehicle['year']); ?></td></tr>
                                    <tr><td><strong>Type:</strong></td><td><?= htmlspecialchars($vehicle['vehicle_type']); ?></td></tr>
                                    <tr><td><strong>Fuel:</strong></td><td><?= htmlspecialchars($vehicle['fuel_type']); ?></td></tr>
                                    <tr><td><strong>Transmission:</strong></td><td><?= htmlspecialchars($vehicle['transmission']); ?></td></tr>
                                    <tr><td><strong>Seats:</strong></td><td><?= htmlspecialchars($vehicle['seat_capacity']); ?></td></tr>
                                    <tr><td><strong>Location:</strong></td><td><?= htmlspecialchars($vehicle['location']); ?></td></tr>
                                </table>
                                
                                <div style="display: flex; gap: 10px; flex-direction: column;">
                                    <a href="<?= APP_BASE_URL ?>/customer/book-vehicle?id=<?= $vehicle['vehicle_id']; ?>" class="btn btn-primary">Book Now</a>
                                    <a href="<?= APP_BASE_URL ?>/customer/compare?remove=<?= $vehicle['vehicle_id']; ?>" class="btn btn-danger" onclick="return confirm('Remove from comparison?')">Remove</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
