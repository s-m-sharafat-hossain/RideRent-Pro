<?php
/**
 * Driver Earnings View
 * 
 * This view displays the earnings overview for drivers.
 * It shows total earnings, paid earnings, pending earnings, and payment history.
 * 
 * @package RideRentPro\Views\Driver
 * @author RideRent Pro Team
 * @version 1.0.0
 * 
 * @var float $totalEarnings Total earnings from all bookings
 * @var float $paidEarnings Earnings from paid bookings
 * @var float $pendingEarnings Earnings from pending payment bookings
 * @var int $paidCount Number of paid bookings
 * @var int $pendingCount Number of pending payment bookings
 * @var array $history Array of payment history with booking details
 * @var string $userName The name of the current driver
 */
$pageTitle = 'My Earnings';
require_once __DIR__ . '/../layouts/main.php';
?>

<!-- Dashboard Container -->
<div class="dashboard-container">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-dollar-sign"></i> My Earnings</h1>
            <p>Track your payment history and earnings</p>
        </div>

        <!-- Earnings Summary -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #6C5CE7, #5B4CE6);">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-content">
                    <h3>Total Earnings</h3>
                    <p>৳<?= number_format($totalEarnings, 2); ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #00C9A7, #009B80);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>Paid Amount</h3>
                    <p>৳<?= number_format($paidEarnings, 2); ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #FD7E14, #E67E22);">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="stat-content">
                    <h3>Pending Amount</h3>
                    <p>৳<?= number_format($pendingEarnings, 2); ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #2F80ED, #1A5BB5);">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <h3>Total Trips</h3>
                    <p><?= $paidCount + $pendingCount; ?></p>
                </div>
            </div>
        </div>

        <!-- Payment History -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Payment History</h3>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Customer</th>
                                <th>Vehicle</th>
                                <th>Date</th>
                                <th>Days</th>
                                <th>Driver Fee</th>
                                <th>Payment Status</th>
                                <th>Booking Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($history)): ?>
                                <?php foreach($history as $row): ?>
                                    <?php
                                    $paymentClass = '';
                                    if ($row['payment_status'] == 'Paid') $paymentClass = 'badge-success';
                                    elseif ($row['payment_status'] == 'Pending') $paymentClass = 'badge-warning';
                                    else $paymentClass = 'badge-secondary';
                                    
                                    $statusClass = '';
                                    if ($row['booking_status'] == 'Confirmed' || $row['booking_status'] == 'Driver_Requested') $statusClass = 'badge-success';
                                    elseif ($row['booking_status'] == 'Completed') $statusClass = 'badge-info';
                                    elseif ($row['booking_status'] == 'Cancelled') $statusClass = 'badge-danger';
                                    else $statusClass = 'badge-danger';
                                    ?>
                                    <tr>
                                        <td>#<?= htmlspecialchars($row['booking_id']); ?></td>
                                        <td><?= htmlspecialchars($row['customer_name']); ?></td>
                                        <td><?= htmlspecialchars($row['vehicle_name']); ?></td>
                                        <td><?= htmlspecialchars($row['booking_date']); ?></td>
                                        <td><?= htmlspecialchars($row['total_days']); ?></td>
                                        <td><strong>৳<?= htmlspecialchars($row['driver_fee']); ?></strong></td>
                                        <td><span class='badge <?= $paymentClass; ?>'><?= htmlspecialchars($row['payment_status']); ?></span></td>
                                        <td><span class='badge <?= $statusClass; ?>'><?= htmlspecialchars($row['booking_status']); ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan='9' style='text-align: center; padding: 30px;'>No earnings history found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
