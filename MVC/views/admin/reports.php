<?php
/**
 * Admin Vehicle Reports View
 * 
 * This view displays vehicle statistics and reports for administrators.
 * It shows total vehicles, available vehicles, booked vehicles, maintenance vehicles,
 * and the latest vehicles added to the system.
 * 
 * @package RideRentPro\Views\Admin
 * @author RideRent Pro Team
 * @version 1.0.0
 * 
 * @var array $stats Array containing vehicle statistics (totalVehicles, availableVehicles, bookedVehicles, maintenanceVehicles, latestVehicles)
 * @var string $userName The name of the current admin user
 */
$pageTitle = 'Vehicle Reports';
require_once __DIR__ . '/../layouts/main.php';
?>

<!-- Dashboard Container -->
<div class="dashboard-container">
    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-chart-bar"></i> Vehicle Reports</h1>
            <p>Vehicle statistics and analytics</p>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #2F80ED, #1A5BB5);">
                    <i class="fas fa-car"></i>
                </div>
                <div class="stat-content">
                    <h3>Total Vehicles</h3>
                    <p><?= htmlspecialchars($stats['totalVehicles']); ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #00C9A7, #009B80);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>Available</h3>
                    <p><?= htmlspecialchars($stats['availableVehicles']); ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #E84393, #D6336C);">
                    <i class="fas fa-calendar-times"></i>
                </div>
                <div class="stat-content">
                    <h3>Booked</h3>
                    <p><?= htmlspecialchars($stats['bookedVehicles']); ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #FD7E14, #E67E22);">
                    <i class="fas fa-tools"></i>
                </div>
                <div class="stat-content">
                    <h3>Maintenance</h3>
                    <p><?= htmlspecialchars($stats['maintenanceVehicles']); ?></p>
                </div>
            </div>
        </div>

        <!-- Latest Vehicles -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Latest Vehicles</h3>
            </div>
            <div class="card-body">
                <div class="vehicle-grid">
                    <?php foreach($stats['latestVehicles'] as $row): ?>
                        <?php
                        $badgeClass = '';
                        if($row['availability'] == "Available") {
                            $badgeClass = 'badge-success';
                        } elseif($row['availability'] == "Booked") {
                            $badgeClass = 'badge-danger';
                        } else {
                            $badgeClass = 'badge-warning';
                        }
                        ?>
                        <div class="vehicle-card">
                            <img src="/public/assets/uploads/<?= htmlspecialchars($row['image']); ?>" alt="<?= htmlspecialchars($row['vehicle_name']); ?>">
                            <div class="vehicle-info">
                                <h4><?= htmlspecialchars($row['vehicle_name']); ?></h4>
                                <p><strong>Brand:</strong> <?= htmlspecialchars($row['brand']); ?></p>
                                <p><strong>Price:</strong> ৳<?= htmlspecialchars($row['price_per_day']); ?>/day</p>
                                <span class='badge <?= $badgeClass; ?>'><?= htmlspecialchars($row['availability']); ?></span>
                                <span class='badge badge-info'><?= htmlspecialchars($row['approval_status']); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>