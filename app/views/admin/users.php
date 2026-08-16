<?php
/**
 * Admin Users Management View
 * 
 * This view displays the user management interface for administrators.
 * It allows admins to view, filter, delete, and change status of users
 * across all roles (admin, customer, driver, owner).
 * 
 * @package RideRentPro\Views\Admin
 * @author RideRent Pro Team
 * @version 1.0.0
 * 
 * @var array $users Array containing users grouped by role (admins, customers, owners, drivers)
 * @var string $filter Current filter applied to the user list (all, admin, customer, driver, owner)
 * @var string $userName The name of the current admin user
 */
$pageTitle = 'Users Management';
require_once __DIR__ . '/../layouts/main.php';
?>

<!-- Dashboard Container -->
<div class="dashboard-container">
    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-users"></i> Users Management</h1>
            <p>Manage all system users</p>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filter Users</h3>
            </div>
            <div class="card-body">
                <div class="filter-tabs">
                    <a href="<?= APP_BASE_URL ?>/admin/users?filter=all" class="btn <?= $filter == 'all' ? 'btn-primary' : 'btn-secondary' ?>">All Users</a>
                    <a href="<?= APP_BASE_URL ?>/admin/users?filter=admin" class="btn <?= $filter == 'admin' ? 'btn-primary' : 'btn-secondary' ?>">Admins</a>
                    <a href="<?= APP_BASE_URL ?>/admin/users?filter=customer" class="btn <?= $filter == 'customer' ? 'btn-primary' : 'btn-secondary' ?>">Customers</a>
                    <a href="<?= APP_BASE_URL ?>/admin/users?filter=driver" class="btn <?= $filter == 'driver' ? 'btn-primary' : 'btn-secondary' ?>">Drivers</a>
                    <a href="<?= APP_BASE_URL ?>/admin/users?filter=owner" class="btn <?= $filter == 'owner' ? 'btn-primary' : 'btn-secondary' ?>">Vehicle Owners</a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Users List</h3>
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
                                <th>Type</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($filter == 'all' || $filter == 'admin'): ?>
                                <?php foreach($users['admins'] as $row): ?>
                                    <?php $statusClass = $row['status'] == 'Active' ? 'badge-success' : 'badge-danger'; ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['admin_id']); ?></td>
                                        <td><?= htmlspecialchars($row['full_name']); ?></td>
                                        <td><?= htmlspecialchars($row['email']); ?></td>
                                        <td><?= htmlspecialchars($row['phone']); ?></td>
                                        <td><span class='badge badge-primary'>Admin</span></td>
                                        <td><span class='badge <?= $statusClass; ?>'><?= htmlspecialchars($row['status']); ?></span></td>
                                        <td>
                                            <a href='<?= APP_BASE_URL ?>/admin/users?id=<?= $row['admin_id']; ?>&type=admin&status=Active' class='btn btn-success btn-sm'>Activate</a>
                                            <a href='<?= APP_BASE_URL ?>/admin/users?id=<?= $row['admin_id']; ?>&type=admin&status=Inactive' class='btn btn-warning btn-sm'>Deactivate</a>
                                            <a href='<?= APP_BASE_URL ?>/admin/users?delete=<?= $row['admin_id']; ?>&type=admin' class='btn btn-danger btn-sm' onclick='return confirm("Are you sure?")'>Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            
                            <?php if($filter == 'all' || $filter == 'customer'): ?>
                                <?php foreach($users['customers'] as $row): ?>
                                    <?php $statusClass = $row['status'] == 'Active' ? 'badge-success' : 'badge-danger'; ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['customer_id']); ?></td>
                                        <td><?= htmlspecialchars($row['full_name']); ?></td>
                                        <td><?= htmlspecialchars($row['email']); ?></td>
                                        <td><?= htmlspecialchars($row['phone_1']); ?></td>
                                        <td><span class='badge badge-info'>Customer</span></td>
                                        <td><span class='badge <?= $statusClass; ?>'><?= htmlspecialchars($row['status']); ?></span></td>
                                        <td>
                                            <a href='<?= APP_BASE_URL ?>/admin/users?id=<?= $row['customer_id']; ?>&type=customer&status=Active' class='btn btn-success btn-sm'>Activate</a>
                                            <a href='<?= APP_BASE_URL ?>/admin/users?id=<?= $row['customer_id']; ?>&type=customer&status=Inactive' class='btn btn-warning btn-sm'>Deactivate</a>
                                            <a href='<?= APP_BASE_URL ?>/admin/users?delete=<?= $row['customer_id']; ?>&type=customer' class='btn btn-danger btn-sm' onclick='return confirm("Are you sure?")'>Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            
                            <?php if($filter == 'all' || $filter == 'driver'): ?>
                                <?php foreach($users['drivers'] as $row): ?>
                                    <?php $statusClass = $row['status'] == 'Active' ? 'badge-success' : 'badge-danger'; ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['driver_id']); ?></td>
                                        <td><?= htmlspecialchars($row['full_name']); ?></td>
                                        <td><?= htmlspecialchars($row['email']); ?></td>
                                        <td><?= htmlspecialchars($row['phone']); ?></td>
                                        <td><span class='badge badge-secondary'>Driver</span></td>
                                        <td><span class='badge <?= $statusClass; ?>'><?= htmlspecialchars($row['status']); ?></span></td>
                                        <td>
                                            <a href='<?= APP_BASE_URL ?>/admin/users?id=<?= $row['driver_id']; ?>&type=driver&status=Active' class='btn btn-success btn-sm'>Activate</a>
                                            <a href='<?= APP_BASE_URL ?>/admin/users?id=<?= $row['driver_id']; ?>&type=driver&status=Inactive' class='btn btn-warning btn-sm'>Deactivate</a>
                                            <a href='<?= APP_BASE_URL ?>/admin/users?delete=<?= $row['driver_id']; ?>&type=driver' class='btn btn-danger btn-sm' onclick='return confirm("Are you sure?")'>Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            
                            <?php if($filter == 'all' || $filter == 'owner'): ?>
                                <?php foreach($users['owners'] as $row): ?>
                                    <?php $statusClass = $row['status'] == 'Active' ? 'badge-success' : 'badge-danger'; ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['owner_id']); ?></td>
                                        <td><?= htmlspecialchars($row['full_name']); ?></td>
                                        <td><?= htmlspecialchars($row['email']); ?></td>
                                        <td><?= htmlspecialchars($row['phone']); ?></td>
                                        <td><span class='badge badge-warning'>Owner</span></td>
                                        <td><span class='badge <?= $statusClass; ?>'><?= htmlspecialchars($row['status']); ?></span></td>
                                        <td>
                                            <a href='<?= APP_BASE_URL ?>/admin/users?id=<?= $row['owner_id']; ?>&type=owner&status=Active' class='btn btn-success btn-sm'>Activate</a>
                                            <a href='<?= APP_BASE_URL ?>/admin/users?id=<?= $row['owner_id']; ?>&type=owner&status=Inactive' class='btn btn-warning btn-sm'>Deactivate</a>
                                            <a href='<?= APP_BASE_URL ?>/admin/users?delete=<?= $row['owner_id']; ?>&type=owner' class='btn btn-danger btn-sm' onclick='return confirm("Are you sure?")'>Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>