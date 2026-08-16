<?php
/**
 * Admin Bookings Management View
 * 
 * This view displays the booking management interface for administrators.
 * It allows admins to view, update status, change payment status, and delete bookings.
 * 
 * @package RideRentPro\Views\Admin
 * @author RideRent Pro Team
 * @version 1.0.0
 * 
 * @var array $bookings Array of all booking records with related customer, vehicle, and driver information
 * @var string $userName The name of the current admin user
 */
$pageTitle = 'Bookings Management';
require_once __DIR__ . '/../layouts/main.php';
?>

<!-- Dashboard Container -->
<div class="dashboard-container">
    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-calendar-check"></i> Bookings Management</h1>
            <p>Manage all vehicle bookings</p>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Bookings List</h3>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Vehicle</th>
                                <th>Driver</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Days</th>
                                <th>Total Price</th>
                                <th>Booking Status</th>
                                <th>Payment Status</th>
                                <th>Payment Method</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($bookings)): ?>
                                <?php foreach($bookings as $row): ?>
                                    <?php
                                    $statusClass = '';
                                    if ($row['booking_status'] == 'Confirmed' || $row['booking_status'] == 'Driver_Requested') $statusClass = 'badge-success';
                                    elseif ($row['booking_status'] == 'Pending') $statusClass = 'badge-warning';
                                    elseif ($row['booking_status'] == 'Completed') $statusClass = 'badge-info';
                                    elseif ($row['booking_status'] == 'Cancelled') $statusClass = 'badge-danger';
                                    else $statusClass = 'badge-danger';

                                    $displayStatus = $row['booking_status'];
                                    if($row['booking_status'] == 'Driver_Requested') $displayStatus = 'Driver Needed';

                                    $paymentClass = '';
                                    if ($row['payment_status'] == 'Paid') $paymentClass = 'badge-success';
                                    elseif ($row['payment_status'] == 'Pending') $paymentClass = 'badge-warning';
                                    else $paymentClass = 'badge-secondary';

                                    $driverDisplay = $row['driver_name'] ? $row['driver_name'] : '<span class="text-muted">Not Assigned</span>';
                                    ?>
                                    <tr>
                                        <td>#<?= htmlspecialchars($row['booking_id']); ?></td>
                                        <td><?= htmlspecialchars($row['customer_name']); ?></td>
                                        <td><?= htmlspecialchars($row['vehicle_name']); ?></td>
                                        <td><?= $driverDisplay; ?></td>
                                        <td><?= htmlspecialchars($row['start_date']); ?></td>
                                        <td><?= htmlspecialchars($row['end_date']); ?></td>
                                        <td><?= htmlspecialchars($row['total_days']); ?></td>
                                        <td>৳<?= htmlspecialchars($row['total_price']); ?></td>
                                        <td><span class='badge <?= $statusClass; ?>'><?= htmlspecialchars($displayStatus); ?></span></td>
                                        <td><span class='badge <?= $paymentClass; ?>'><?= htmlspecialchars($row['payment_status']); ?></span></td>
                                        <td><?= htmlspecialchars($row['payment_method'] ?? '-'); ?></td>
                                        <td>
                                            <?php if($row['booking_status'] == 'Driver_Requested'): ?>
                                                <a href='<?= APP_BASE_URL ?>/admin/driver-assignment' class='btn btn-primary btn-sm'>Assign Driver</a>
                                            <?php elseif($row['booking_status'] == 'Confirmed'): ?>
                                                <a href='<?= APP_BASE_URL ?>/admin/bookings?id=<?= $row['booking_id']; ?>&status=Completed' class='btn btn-info btn-sm'>Complete</a>
                                            <?php elseif($row['booking_status'] == 'Pending'): ?>
                                                <a href='<?= APP_BASE_URL ?>/admin/bookings?id=<?= $row['booking_id']; ?>&status=Confirmed' class='btn btn-success btn-sm'>Confirm</a>
                                            <?php endif; ?>
                                            
                                            <?php if($row['booking_status'] != 'Completed' && $row['booking_status'] != 'Cancelled'): ?>
                                                <a href='<?= APP_BASE_URL ?>/admin/bookings?id=<?= $row['booking_id']; ?>&status=Cancelled' class='btn btn-danger btn-sm'>Cancel</a>
                                            <?php endif; ?>
                                            
                                            <?php if($row['payment_status'] == 'Pending'): ?>
                                                <a href='<?= APP_BASE_URL ?>/admin/bookings?id=<?= $row['booking_id']; ?>&payment_status=Paid' class='btn btn-success btn-sm'>Mark Paid</a>
                                            <?php endif; ?>
                                            
                                            <a href='<?= APP_BASE_URL ?>/admin/bookings?delete=<?= $row['booking_id']; ?>' class='btn btn-danger btn-sm' onclick='return confirm("Are you sure?")'>Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan='11' style='text-align: center; padding: 30px;'>No bookings found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>