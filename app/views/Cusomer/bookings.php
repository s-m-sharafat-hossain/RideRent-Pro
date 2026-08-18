<?php
/**
 * Customer Bookings View
 * 
 * This view displays the booking history for the current customer.
 * It shows all bookings made by the customer with their status and details.
 * 
 * @package RideRentPro\Views\Customer
 * @author RideRent Pro Team
 * @version 1.0.0
 * 
 * @var array $bookings Array of booking records with vehicle and driver information
 * @var string $userName The name of the current customer
 */
$pageTitle = 'My Bookings';
require_once __DIR__ . '/../layouts/main.php';
?>

<!-- Dashboard Container -->
<div class="dashboard-container">
    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-calendar-check"></i> My Bookings</h1>
            <p>View and manage your vehicle bookings</p>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Booking History</h3>
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
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($bookings)): ?>
                                <?php foreach($bookings as $row): ?>
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
                                        <td>
                                            <a href="/customer/add-review?booking_id=<?= $row['booking_id']; ?>" class="btn btn-primary btn-sm">Add Review</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan='8' style='text-align: center;'>No bookings found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>