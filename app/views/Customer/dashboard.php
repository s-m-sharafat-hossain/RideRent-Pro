<?php
/**
 * Customer Dashboard View
 * 
 * This view displays the customer dashboard with booking statistics and recent bookings.
 * It shows total bookings, active bookings, total spending, and recent booking history.
 * 
 * @package RideRentPro\Views\Customer
 * @author RideRent Pro Team
 * @version 1.0.0
 * 
 * @var int $totalBookings Total number of bookings made by the customer
 * @var int $activeBookings Number of currently active bookings
 * @var float $totalSpending Total amount spent on bookings
 * @var array $recentBookings Array of recent booking records
 * @var string $userName The name of the current customer
 * @var string $userRole The role of the current user (customer)
 */
$pageTitle = 'Customer Dashboard';
require_once __DIR__ . '/../layouts/main.php';
?>

<!-- Dashboard Container -->
<div class="dashboard-container">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-tachometer-alt"></i> Customer Dashboard</h1>
            <p>Welcome back, <?= htmlspecialchars($userName); ?>!</p>
            <div style="margin-top: 14px;">
                <a href="<?= APP_BASE_URL ?>/customer/profile" class="btn btn-primary">
                    <i class="fas fa-user"></i> My Profile
                </a>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #2F80ED, #1A5BB5);">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <h3>Total Bookings</h3>
                    <p><?= htmlspecialchars($totalBookings); ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #00C9A7, #009B80);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3>Active Bookings</h3>
                    <p><?= htmlspecialchars($activeBookings); ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #6C5CE7, #5B4CE6);">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-content">
                    <h3>Total Spent</h3>
                    <p>$<?= number_format($totalSpending, 2); ?></p>
                </div>
            </div>
        </div>

        <!-- Recent Bookings Table -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-clock"></i> Recent Bookings</h3>
                <p class="card-subtitle">Latest 5 bookings</p>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>#Booking ID</th>
                                <th>Vehicle</th>
                                <th>Driver</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Total Price</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($recentBookings)): ?>
                                <?php foreach($recentBookings as $row): ?>
                                    <?php
                                    $statusClass = '';
                                    if ($row['booking_status'] == 'Confirmed') $statusClass = 'badge-success';
                                    elseif ($row['booking_status'] == 'Pending') $statusClass = 'badge-warning';
                                    elseif ($row['booking_status'] == 'Completed') $statusClass = 'badge-info';
                                    else $statusClass = 'badge-danger';
                                    ?>
                                    <tr>
                                        <td>#<?= htmlspecialchars($row['booking_id']); ?></td>
                                        <td><?= htmlspecialchars($row['vehicle_name']); ?></td>
                                        <td><?= htmlspecialchars($row['driver_name'] ?? 'N/A'); ?></td>
                                        <td><?= htmlspecialchars($row['start_date']); ?></td>
                                        <td><?= htmlspecialchars($row['end_date']); ?></td>
                                        <td>৳<?= htmlspecialchars($row['total_price']); ?></td>
                                        <td><span class="badge <?= $statusClass; ?>"><?= htmlspecialchars($row['booking_status']); ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7">No recent bookings found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
