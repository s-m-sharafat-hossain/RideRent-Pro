<?php
/**
 * Driver Dashboard View
 * 
 * This view displays the driver dashboard with statistics and recent bookings.
 * It shows total bookings, active bookings, completed bookings, total earnings,
 * pending earnings, and recent booking history.
 * 
 * @package RideRentPro\Views\Driver
 * @author RideRent Pro Team
 * @version 1.0.0
 * 
 * @var int $totalBookings Total number of bookings assigned to the driver
 * @var int $activeBookings Number of currently active bookings
 * @var int $completedBookings Number of completed bookings
 * @var float $totalEarnings Total earnings from paid bookings
 * @var float $pendingEarnings Earnings from pending payment bookings
 * @var array $driverInfo Driver personal information and profile data
 * @var array $recentBookings Array of recent booking records with customer and vehicle information
 * @var string $userName The name of the current driver
 * @var string $userRole The role of the current user (driver)
 */
$pageTitle = 'Driver Dashboard';
require_once __DIR__ . '/../layouts/main.php';
?>

<!-- Dashboard Container -->
<div class="dashboard-container">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-tachometer-alt"></i> Driver Dashboard</h1>
            <p>Welcome back, <?= htmlspecialchars($userName); ?>!</p>
            <div style="margin-top: 14px;">
                <a href="<?= APP_BASE_URL ?>/driver/profile" class="btn btn-primary">
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
                    <h3>Active</h3>
                    <p><?= htmlspecialchars($activeBookings); ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #00C9A7, #009B80);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>Completed</h3>
                    <p><?= htmlspecialchars($completedBookings); ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #6C5CE7, #5B4CE6);">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-content">
                    <h3>Paid Earnings</h3>
                    <p>৳<?= number_format($totalEarnings, 2); ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #FD7E14, #E67E22);">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="stat-content">
                    <h3>Pending</h3>
                    <p>৳<?= number_format($pendingEarnings, 2); ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #E84393, #D63031);">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-content">
                    <h3>My Rating</h3>
                    <p><?= $driverInfo['rating']; ?> ⭐</p>
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
                                <th>Customer</th>
                                <th>Vehicle</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Fee</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($recentBookings)): ?>
                                <?php foreach($recentBookings as $row): ?>
                                    <?php
                                    $statusClass = '';
                                    if ($row['booking_status'] == 'Confirmed' || $row['booking_status'] == 'Driver_Requested') $statusClass = 'badge-success';
                                    elseif ($row['booking_status'] == 'Pending') $statusClass = 'badge-warning';
                                    elseif ($row['booking_status'] == 'Completed') $statusClass = 'badge-info';
                                    elseif ($row['booking_status'] == 'Cancelled') $statusClass = 'badge-danger';
                                    else $statusClass = 'badge-danger';

                                    $displayStatus = $row['booking_status'];
                                    if($row['booking_status'] == 'Driver_Requested') $displayStatus = 'Assigned';

                                    $paymentClass = '';
                                    if ($row['payment_status'] == 'Paid') $paymentClass = 'badge-success';
                                    elseif ($row['payment_status'] == 'Pending') $paymentClass = 'badge-warning';
                                    else $paymentClass = 'badge-secondary';
                                    ?>
                                    <tr>
                                        <td>#<?= htmlspecialchars($row['booking_id']); ?></td>
                                        <td><?= htmlspecialchars($row['customer_name']); ?><br><small>📞 <?= htmlspecialchars($row['customer_phone']); ?></small></td>
                                        <td><?= htmlspecialchars($row['vehicle_name']); ?><br><small><?= htmlspecialchars($row['brand']); ?> <?= htmlspecialchars($row['vehicle_type']); ?></small></td>
                                        <td><?= htmlspecialchars($row['start_date']); ?></td>
                                        <td><?= htmlspecialchars($row['end_date']); ?></td>
                                        <td><span class='badge <?= $statusClass; ?>'><?= htmlspecialchars($displayStatus); ?></span></td>
                                        <td><span class='badge <?= $paymentClass; ?>'><?= htmlspecialchars($row['payment_status']); ?></span></td>
                                        <td>৳<?= htmlspecialchars($row['driver_fee']); ?></td>
                                        <td><a href='<?= APP_BASE_URL ?>/driver/booking-details?id=<?= $row['booking_id']; ?>' class='btn btn-info btn-sm'><i class='fas fa-eye'></i> View</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan='9'>No recent bookings found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
