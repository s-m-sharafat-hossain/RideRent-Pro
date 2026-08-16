<?php
/**
 * Admin Vehicle Approvals View
 * 
 * This view displays the vehicle approval interface for administrators.
 * It allows admins to approve, reject, or delete pending vehicle submissions from owners.
 * 
 * @package RideRentPro\Views\Admin
 * @author RideRent Pro Team
 * @version 1.0.0
 * 
 * @var array $pendingVehicles Array of vehicles with 'Pending' approval status
 * @var array $allVehicles Array of all vehicles regardless of approval status
 * @var array|null $message Optional flash message array
 * @var string $userName The name of the current admin user
 */
$pageTitle = 'Vehicle Approvals';
require_once __DIR__ . '/../layouts/main.php';
?>

<!-- Dashboard Container -->
<div class="dashboard-container">
    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-check-circle"></i> Vehicle Approvals</h1>
            <p>Review and approve vehicle owner requests</p>
        </div>

        <?php if(isset($message) && $message): ?>
            <div class="alert alert-<?= $message['type'] ?? 'success'; ?>">
                <?= htmlspecialchars($message['message']); ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pending Approvals</h3>
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
                                <th>Owner</th>
                                <th>Type</th>
                                <th>Price/Day</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($pendingVehicles)): ?>
                                <?php foreach($pendingVehicles as $row): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['vehicle_id']); ?></td>
                                        <td><?= htmlspecialchars($row['vehicle_name']); ?></td>
                                        <td><?= htmlspecialchars($row['brand']); ?></td>
                                        <td><?= htmlspecialchars($row['model']); ?></td>
                                        <td><?= htmlspecialchars($row['owner_name']); ?></td>
                                        <td><?= htmlspecialchars($row['vehicle_type']); ?></td>
                                        <td>৳<?= number_format($row['price_per_day'], 2); ?></td>
                                        <td><?= htmlspecialchars($row['location']); ?></td>
                                        <td><span class='badge badge-warning'>Pending</span></td>
                                        <td>
                                            <a href="<?= APP_BASE_URL ?>/admin/vehicle-approvals?action=approve&id=<?= $row['vehicle_id']; ?>" class="btn btn-success btn-sm" onclick="return confirm('Approve this vehicle?')"><i class="fas fa-check"></i> Approve</a>
                                            <a href="<?= APP_BASE_URL ?>/admin/vehicle-approvals?action=reject&id=<?= $row['vehicle_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Reject this vehicle?')"><i class="fas fa-times"></i> Reject</a>
                                            <a href="<?= APP_BASE_URL ?>/admin/vehicle-approvals?action=delete&id=<?= $row['vehicle_id']; ?>" class="btn btn-secondary btn-sm" onclick="return confirm('Delete this vehicle? This will remove it from the owner\'s account.')"><i class="fas fa-trash"></i> Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan='11' style='text-align: center; padding: 30px;'>No pending vehicle approvals.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Vehicles</h3>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Vehicle Name</th>
                                <th>Brand</th>
                                <th>Owner</th>
                                <th>Type</th>
                                <th>Price/Day</th>
                                <th>Approval Status</th>
                                <th>Availability</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($allVehicles)): ?>
                                <?php foreach($allVehicles as $row): ?>
                                    <?php
                                    $approvalBadge = '';
                                    if($row['approval_status'] == 'Approved') $approvalBadge = 'badge-success';
                                    elseif($row['approval_status'] == 'Rejected') $approvalBadge = 'badge-danger';
                                    else $approvalBadge = 'badge-warning';
                                    
                                    $availabilityBadge = '';
                                    if($row['availability'] == 'Available') $availabilityBadge = 'badge-success';
                                    elseif($row['availability'] == 'Booked') $availabilityBadge = 'badge-danger';
                                    else $availabilityBadge = 'badge-warning';
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['vehicle_id']); ?></td>
                                        <td><?= htmlspecialchars($row['vehicle_name']); ?></td>
                                        <td><?= htmlspecialchars($row['brand']); ?></td>
                                        <td><?= htmlspecialchars($row['owner_name']); ?></td>
                                        <td><?= htmlspecialchars($row['vehicle_type']); ?></td>
                                        <td>৳<?= number_format($row['price_per_day'], 2); ?></td>
                                        <td><span class='badge <?= $approvalBadge; ?>'><?= htmlspecialchars($row['approval_status']); ?></span></td>
                                        <td><span class='badge <?= $availabilityBadge; ?>'><?= htmlspecialchars($row['availability']); ?></span></td>
                                        <td>
                                            <a href="<?= APP_BASE_URL ?>/admin/vehicle-approvals?action=delete&id=<?= $row['vehicle_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this vehicle? This will permanently remove it from the owner\'s account.')"><i class="fas fa-trash"></i> Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan='9' style='text-align: center; padding: 30px;'>No vehicles found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>