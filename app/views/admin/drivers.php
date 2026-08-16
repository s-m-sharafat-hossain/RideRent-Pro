<?php
/**
 * Admin Drivers Management View
 * 
 * This view displays the driver management interface for administrators.
 * It allows admins to view, delete, change status, and update availability of drivers.
 * 
 * @package RideRentPro\Views\Admin
 * @author RideRent Pro Team
 * @version 1.0.0
 * 
 * @var array $drivers Array of all driver records with their information
 * @var string $userName The name of the current admin user
 */
$pageTitle = 'Drivers Management';
require_once __DIR__ . '/../layouts/main.php';
?>

<!-- Dashboard Container -->
<div class="dashboard-container">
    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-id-card"></i> Drivers Management</h1>
            <p>Manage all system drivers</p>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Drivers List</h3>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>License</th>
                                <th>Experience</th>
                                <th>Rating</th>
                                <th>Availability</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($drivers)): ?>
                                <?php foreach($drivers as $row): ?>
                                    <?php
                                    $statusClass = $row['status'] == 'Active' ? 'badge-success' : 'badge-danger';
                                    $availClass = $row['availability'] == 'Available' ? 'badge-success' : 'badge-warning';
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['driver_id']); ?></td>
                                        <td><?= htmlspecialchars($row['full_name']); ?></td>
                                        <td><?= htmlspecialchars($row['email']); ?></td>
                                        <td><?= htmlspecialchars($row['phone']); ?></td>
                                        <td><?= htmlspecialchars($row['license_number']); ?></td>
                                        <td><?= htmlspecialchars($row['experience_years']); ?> years</td>
                                        <td><?= htmlspecialchars($row['rating']); ?> ⭐</td>
                                        <td><span class='badge <?= $availClass; ?>'><?= htmlspecialchars($row['availability']); ?></span></td>
                                        <td><span class='badge <?= $statusClass; ?>'><?= htmlspecialchars($row['status']); ?></span></td>
                                        <td>
                                            <a href='<?= APP_BASE_URL ?>/admin/drivers?id=<?= $row['driver_id']; ?>&availability=Available' class='btn btn-success btn-sm'>Available</a>
                                            <a href='<?= APP_BASE_URL ?>/admin/drivers?id=<?= $row['driver_id']; ?>&availability=Unavailable' class='btn btn-warning btn-sm'>Unavailable</a>
                                            <a href='<?= APP_BASE_URL ?>/admin/drivers?id=<?= $row['driver_id']; ?>&status=Active' class='btn btn-info btn-sm'>Activate</a>
                                            <a href='<?= APP_BASE_URL ?>/admin/drivers?id=<?= $row['driver_id']; ?>&status=Inactive' class='btn btn-secondary btn-sm'>Deactivate</a>
                                            <a href='<?= APP_BASE_URL ?>/admin/drivers?delete=<?= $row['driver_id']; ?>' class='btn btn-danger btn-sm' onclick='return confirm("Are you sure?")'>Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan='7' style='text-align: center; padding: 30px;'>No drivers found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>