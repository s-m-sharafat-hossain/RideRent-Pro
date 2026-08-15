<?php
/**
 * Admin Ratings Overview View
 * 
 * This view displays the ratings overview for administrators.
 * It shows overall rating statistics across the platform including
 * average ratings for vehicles and drivers.
 * 
 * @package RideRentPro\Views\Admin
 * @author RideRent Pro Team
 * @version 1.0.0
 * 
 * @var array $ratings Array containing rating statistics and data
 * @var string $userName The name of the current admin user
 */
$pageTitle = 'Ratings Overview';
require_once __DIR__ . '/../layouts/main.php';
?>

<!-- Dashboard Container -->
<div class="dashboard-container">
    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-star-half-alt"></i> Ratings Overview</h1>
            <p>Driver and vehicle ratings statistics</p>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #2F80ED, #1A5BB5);">
                    <i class="fas fa-id-card"></i>
                </div>
                <div class="stat-content">
                    <h3>Total Drivers</h3>
                    <p><?= $total_drivers; ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #FD7E14, #E67E22);">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-content">
                    <h3>Avg Driver Rating</h3>
                    <p><?= $avg_driver_rating; ?> ⭐</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #00C9A7, #009B80);">
                    <i class="fas fa-car"></i>
                </div>
                <div class="stat-content">
                    <h3>Total Vehicles</h3>
                    <p><?= $total_vehicles; ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #E84393, #D63031);">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-content">
                    <h3>Avg Vehicle Rating</h3>
                    <p><?= $avg_vehicle_rating; ?> ⭐</p>
                </div>
            </div>
        </div>

        <!-- Driver Ratings Table -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-id-card"></i> Driver Ratings</h3>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Experience</th>
                                <th>Rating</th>
                                <th>Rating Count</th>
                                <th>Availability</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($driverRatings)): ?>
                                <?php foreach($driverRatings as $row): ?>
                                    <?php
                                    $availClass = $row['availability'] == 'Available' ? 'badge-success' : 'badge-warning';
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['driver_id']); ?></td>
                                        <td><?= htmlspecialchars($row['full_name']); ?></td>
                                        <td><?= htmlspecialchars($row['email']); ?></td>
                                        <td><?= htmlspecialchars($row['experience_years']); ?> years</td>
                                        <td><span style='color: #FD7E14;'><?= number_format($row['rating'], 2); ?> &#9733;</span></td>
                                        <td><?= $row['rating_count']; ?></td>
                                        <td><span class='badge <?= $availClass; ?>'><?= htmlspecialchars($row['availability']); ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan='7' style='text-align: center; padding: 30px;'>No drivers found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Vehicle Ratings Table -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-car"></i> Vehicle Ratings</h3>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Vehicle Name</th>
                                <th>Brand</th>
                                <th>Model</th>
                                <th>Type</th>
                                <th>Rating</th>
                                <th>Rating Count</th>
                                <th>Availability</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($vehicleRatings)): ?>
                                <?php foreach($vehicleRatings as $row): ?>
                                    <?php
                                    $availClass = $row['availability'] == 'Available' ? 'badge-success' : 'badge-warning';
                                    $statusClass = $row['approval_status'] == 'Approved' ? 'badge-success' : 'badge-warning';
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['vehicle_id']); ?></td>
                                        <td><?= htmlspecialchars($row['vehicle_name']); ?></td>
                                        <td><?= htmlspecialchars($row['brand']); ?></td>
                                        <td><?= htmlspecialchars($row['model']); ?></td>
                                        <td><?= htmlspecialchars($row['vehicle_type']); ?></td>
                                        <td><span style='color: #FD7E14;'><?= number_format($row['rating'], 2); ?> &#9733;</span></td>
                                        <td><?= $row['rating_count']; ?></td>
                                        <td><span class='badge <?= $availClass; ?>'><?= htmlspecialchars($row['availability']); ?></span></td>
                                        <td><span class='badge <?= $statusClass; ?>'><?= htmlspecialchars($row['approval_status']); ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan='9' style='text-align: center; padding: 30px;'>No vehicles found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>