<?php
/**
 * Admin Driver Assignment View
 * 
 * This view displays the driver assignment interface for administrators.
 * It allows admins to assign drivers to bookings that require drivers,
 * and remove drivers from existing bookings.
 * 
 * @package RideRentPro\Views\Admin
 * @author RideRent Pro Team
 * @version 1.0.0
 * 
 * @var array $needDriver Array of bookings that require driver assignment
 * @var array $availableDrivers Array of available drivers for assignment
 * @var string $userName The name of the current admin user
 */
$pageTitle = 'Driver Assignment';
require_once __DIR__ . '/../layouts/main.php';
?>

<!-- Dashboard Container -->
<div class="dashboard-container">
    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-user-plus"></i> Driver Assignment</h1>
            <p>Assign drivers to bookings that require them</p>
        </div>

        <?php if(isset($message) && $message): ?>
            <div class="alert alert-<?= $message['type'] ?? 'success'; ?>">
                <?= htmlspecialchars($message['message']); ?>
            </div>
        <?php endif; ?>

        <!-- Bookings Needing Drivers -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Bookings Requiring Drivers</h3>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Customer</th>
                                <th>Vehicle</th>
                                <th>Dates</th>
                                <th>Location</th>
                                <th>Current Driver</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($needDriver)): ?>
                                <?php foreach($needDriver as $row): ?>
                                    <?php
                                    $driver_name = "Not Assigned";
                                    ?>
                                    <tr>
                                        <td>#<?= htmlspecialchars($row['booking_id']); ?></td>
                                        <td><?= htmlspecialchars($row['customer_name']); ?></td>
                                        <td><?= htmlspecialchars($row['vehicle_name']); ?> (<?= htmlspecialchars($row['vehicle_type']); ?>)</td>
                                        <td><?= htmlspecialchars($row['start_date']); ?> to <?= htmlspecialchars($row['end_date']); ?></td>
                                        <td><?= htmlspecialchars($row['pickup_location']); ?></td>
                                        <td><?= htmlspecialchars($driver_name); ?></td>
                                        <td>
                                            <button onclick="showAssignModal(<?= $row['booking_id']; ?>)" class="btn btn-primary btn-sm"><i class="fas fa-user-plus"></i> Assign Driver</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan='7' style='text-align: center; padding: 30px;'>No bookings requiring driver assignment.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Active Driver Assignments -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Active Driver Assignments</h3>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Customer</th>
                                <th>Vehicle</th>
                                <th>Driver</th>
                                <th>Dates</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($active)): ?>
                                <?php foreach($active as $row): ?>
                                    <tr>
                                        <td>#<?= htmlspecialchars($row['booking_id']); ?></td>
                                        <td><?= htmlspecialchars($row['customer_name']); ?></td>
                                        <td><?= htmlspecialchars($row['vehicle_name']); ?></td>
                                        <td><?= htmlspecialchars($row['driver_name']); ?></td>
                                        <td><?= htmlspecialchars($row['start_date']); ?> to <?= htmlspecialchars($row['end_date']); ?></td>
                                        <td><span class='badge badge-success'>Active</span></td>
                                        <td>
                                            <a href="<?= APP_BASE_URL ?>/admin/driver-assignment?remove_driver=1&booking_id=<?= $row['booking_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Remove driver from this booking?')"><i class="fas fa-user-minus"></i> Remove</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan='7' style='text-align: center; padding: 30px;'>No active driver assignments.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Driver Assignment Modal -->
<div id="assignModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
    <div style="position: relative; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 10px; width: 400px; max-width: 90%;">
        <h3>Assign Driver</h3>
        <form method="POST">
            <input type="hidden" name="booking_id" id="modal_booking_id">
            <div class="form-group">
                <label>Select Available Driver</label>
                <select name="driver_id" class="form-select" required>
                    <?php if(!empty($availableDrivers)): ?>
                        <?php foreach($availableDrivers as $driver): ?>
                            <option value="<?= $driver['driver_id']; ?>"><?= htmlspecialchars($driver['full_name']); ?> (<?= htmlspecialchars($driver['phone']); ?>)</option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">No available drivers</option>
                    <?php endif; ?>
                </select>
            </div>
            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" name="assign_driver" class="btn btn-primary" style="flex: 1;">Assign</button>
                <button type="button" onclick="hideAssignModal()" class="btn btn-secondary" style="flex: 1;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function showAssignModal(bookingId) {
    document.getElementById('modal_booking_id').value = bookingId;
    document.getElementById('assignModal').style.display = 'block';
}

function hideAssignModal() {
    document.getElementById('assignModal').style.display = 'none';
}
</script>