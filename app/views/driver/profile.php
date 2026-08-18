<?php
/**
 * Driver Profile View
 *
 * This view displays the driver profile with identity, contact,
 * verification and vehicle association information.
 *
 * @package RideRentPro\Views\Driver
 * @author RideRent Pro Team
 * @version 1.1.0
 *
 * @var array $driver Driver personal information and profile data
 * @var array $vehicleAssociation Vehicle association summary
 * @var array|null $recentVehicle Most recent associated vehicle
 * @var array|null $flash Optional flash message
 * @var string $userName The name of the current driver
 */
$pageTitle = 'My Profile';
require_once __DIR__ . '/../layouts/main.php';

$statusRaw = strtolower(trim($driver['status'] ?? 'pending'));
$statusBadgeClass = 'badge-warning';
$statusLabel = 'Pending Verification';

if ($statusRaw === 'active' || $statusRaw === 'verified' || $statusRaw === 'approved') {
    $statusBadgeClass = 'badge-success';
    $statusLabel = 'Verified';
} elseif ($statusRaw === 'rejected' || $statusRaw === 'inactive' || $statusRaw === 'suspended') {
    $statusBadgeClass = 'badge-danger';
    $statusLabel = ucfirst($statusRaw);
}
?>

<!-- Dashboard Container -->
<div class="dashboard-container">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-user"></i> My Profile</h1>
            <p>Identity, contact details, verification, and vehicle associations</p>
        </div>

        <?php if(isset($flash) && $flash): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['type'] ?? 'info'); ?>" style="margin-bottom: 20px;">
                <i class="fas fa-<?= ($flash['type'] ?? 'info') === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                <?= htmlspecialchars($flash['message'] ?? ''); ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body" style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
                <div style="text-align: center; border-right: 1px solid var(--border-color); padding-right: 20px;">
                    <img src="https://via.placeholder.com/150x150?text=Driver" alt="Driver profile" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin-bottom: 20px;">
                    <h3 style="margin-bottom: 6px;"><?= htmlspecialchars($driver['full_name']); ?></h3>
                    <p style="color: var(--medium-gray); margin-bottom: 12px;"><?= htmlspecialchars($driver['email']); ?></p>

                    <div style="margin-bottom: 10px;">
                        <span class="badge <?= $statusBadgeClass; ?>" style="font-size: 13px; padding: 8px 14px;">
                            <i class="fas fa-shield-alt"></i> <?= htmlspecialchars($statusLabel); ?>
                        </span>
                    </div>

                    <p style="margin-top: 14px;"><strong>Rating:</strong> <?= number_format((float)($driver['rating'] ?? 0), 1); ?> ⭐ (<?= (int)($driver['rating_count'] ?? 0); ?> reviews)</p>
                    <p><strong>Experience:</strong> <?= (int)($driver['experience_years'] ?? 0); ?> years</p>
                    <p><strong>License:</strong> <?= !empty($driver['license_number']) ? htmlspecialchars($driver['license_number']) : 'N/A'; ?></p>
                </div>

                <div>
                    <h3 style="margin-bottom: 12px;">Identity</h3>
                    <table style="width: 100%; margin-bottom: 24px;">
                        <tr>
                            <th style="width: 30%; padding: 12px; background: var(--off-white); color: var(--dark-gray); font-weight: 600;">Driver ID</th>
                            <td style="padding: 12px; color: var(--dark-gray);"><?= htmlspecialchars($driver['driver_id']); ?></td>
                        </tr>
                        <tr>
                            <th style="padding: 12px; background: var(--off-white); color: var(--dark-gray); font-weight: 600;">Username</th>
                            <td style="padding: 12px; color: var(--dark-gray);"><?= htmlspecialchars($driver['username']); ?></td>
                        </tr>
                        <tr>
                            <th style="padding: 12px; background: var(--off-white); color: var(--dark-gray); font-weight: 600;">Email</th>
                            <td style="padding: 12px; color: var(--dark-gray);"><?= htmlspecialchars($driver['email']); ?></td>
                        </tr>
                        <tr>
                            <th style="padding: 12px; background: var(--off-white); color: var(--dark-gray); font-weight: 600;">Member Since</th>
                            <td style="padding: 12px; color: var(--dark-gray);"><?= !empty($driver['created_at']) ? date('F j, Y', strtotime($driver['created_at'])) : 'N/A'; ?></td>
                        </tr>
                    </table>

                    <h3 style="margin-bottom: 12px;">Contact Information</h3>
                    <table style="width: 100%; margin-bottom: 24px;">
                        <tr>
                            <th style="padding: 12px; background: var(--off-white); color: var(--dark-gray); font-weight: 600;">Phone</th>
                            <td style="padding: 12px; color: var(--dark-gray);"><?= !empty($driver['phone']) ? htmlspecialchars($driver['phone']) : 'N/A'; ?></td>
                        </tr>
                        <tr>
                            <th style="padding: 12px; background: var(--off-white); color: var(--dark-gray); font-weight: 600;">NID Number</th>
                            <td style="padding: 12px; color: var(--dark-gray);"><?= !empty($driver['nid_number']) ? htmlspecialchars($driver['nid_number']) : 'N/A'; ?></td>
                        </tr>
                        <tr>
                            <th style="padding: 12px; background: var(--off-white); color: var(--dark-gray); font-weight: 600;">Address</th>
                            <td style="padding: 12px; color: var(--dark-gray);"><?= !empty($driver['address']) ? htmlspecialchars($driver['address']) : 'N/A'; ?></td>
                        </tr>
                    </table>

                    <h3 style="margin-bottom: 12px;">Vehicle Association</h3>
                    <table style="width: 100%; margin-bottom: 18px;">
                        <tr>
                            <th style="padding: 12px; background: var(--off-white); color: var(--dark-gray); font-weight: 600;">Associated Vehicles</th>
                            <td style="padding: 12px; color: var(--dark-gray);"><?= (int)($vehicleAssociation['vehicle_count'] ?? 0); ?></td>
                        </tr>
                        <tr>
                            <th style="padding: 12px; background: var(--off-white); color: var(--dark-gray); font-weight: 600;">Vehicle List</th>
                            <td style="padding: 12px; color: var(--dark-gray);"><?= !empty($vehicleAssociation['vehicle_names']) ? htmlspecialchars($vehicleAssociation['vehicle_names']) : 'No associated vehicles yet'; ?></td>
                        </tr>
                        <tr>
                            <th style="padding: 12px; background: var(--off-white); color: var(--dark-gray); font-weight: 600;">Most Recent Assignment</th>
                            <td style="padding: 12px; color: var(--dark-gray);">
                                <?php if(!empty($recentVehicle)): ?>
                                    <?= htmlspecialchars(($recentVehicle['vehicle_name'] ?? 'Vehicle') . ' (' . ($recentVehicle['brand'] ?? 'N/A') . ' ' . ($recentVehicle['model'] ?? 'N/A') . ')'); ?>
                                    <span class="badge badge-info" style="margin-left: 8px;"><?= htmlspecialchars($recentVehicle['booking_status'] ?? 'Unknown'); ?></span>
                                <?php else: ?>
                                    No recent vehicle assignment
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>

                    <hr style="margin: 24px 0;">

                    <form method="POST" action="<?= APP_BASE_URL ?>/driver/profile" id="edit-driver-profile" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div class="form-group">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($driver['full_name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($driver['phone'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($driver['address'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Experience (Years)</label>
                            <input type="number" min="0" max="60" name="experience_years" class="form-control" value="<?= (int)($driver['experience_years'] ?? 0); ?>">
                        </div>

                        <div style="grid-column: span 2; text-align: right; margin-top: 4px;">
                            <button type="submit" name="update_profile" class="btn btn-primary">
                                <i class="fas fa-pen"></i> Edit Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
