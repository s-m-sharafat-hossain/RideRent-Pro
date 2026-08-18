<?php
/**
 * Customer Profile View
 * 
 * This view displays the customer profile management interface.
 * It allows customers to update their personal information, contact details,
 * and profile image. It also shows customer statistics.
 * 
 * @package RideRentPro\Views\Customer
 * @author RideRent Pro Team
 * @version 1.0.0
 * 
 * @var array $customerInfo Customer personal information and profile data
 * @var array $stats Customer statistics (total_bookings, completed_bookings, total_spent, avg_vehicle_rating)
 * @var string $userName The name of the current customer
 */
$pageTitle = 'My Profile';
require_once __DIR__ . '/../layouts/main.php';
?>

<!-- Dashboard Container -->
<div class="dashboard-container">
    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-user"></i> My Profile</h1>
            <p>View and update your profile information</p>
        </div>

        <div class="card">
            <div class="card-body" style="display: grid; grid-template-columns: 1fr 2fr; gap: 40px;">
                <div style="text-align: center;">
                    <?php if(!empty($customerInfo['profile_image'])): ?>
                        <img src="/public/assets/uploads/<?= htmlspecialchars($customerInfo['profile_image']); ?>" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin-bottom: 20px;">
                    <?php else: ?>
                        <img src="https://via.placeholder.com/150x150?text=Customer" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin-bottom: 20px;">
                    <?php endif; ?>
                    <h3><?= htmlspecialchars($customerInfo['full_name']); ?></h3>
                    <p style="color: var(--medium-gray);"><?= htmlspecialchars($customerInfo['email']); ?></p>
                    <p><span class="badge badge-success"><?= htmlspecialchars($customerInfo['status']); ?></span></p>
                    
                    <div style="margin-top: 20px; padding: 15px; background: var(--off-white); border-radius: var(--radius-md);">
                        <h4 style="margin-bottom: 15px;">Your Statistics</h4>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            <div style="text-align: center; padding: 10px; background: white; border-radius: var(--radius-sm);">
                                <i class="fas fa-calendar-check" style="color: #2F80ED; font-size: 20px;"></i>
                                <p style="margin: 5px 0 0 0; font-size: 14px;">Total Bookings</p>
                                <strong style="font-size: 18px;"><?= $stats['total_bookings']; ?></strong>
                            </div>
                            <div style="text-align: center; padding: 10px; background: white; border-radius: var(--radius-sm);">
                                <i class="fas fa-check-circle" style="color: #00C9A7; font-size: 20px;"></i>
                                <p style="margin: 5px 0 0 0; font-size: 14px;">Completed</p>
                                <strong style="font-size: 18px;"><?= $stats['completed_bookings']; ?></strong>
                            </div>
                            <div style="text-align: center; padding: 10px; background: white; border-radius: var(--radius-sm);">
                                <i class="fas fa-dollar-sign" style="color: #6C5CE7; font-size: 20px;"></i>
                                <p style="margin: 5px 0 0 0; font-size: 14px;">Total Spent</p>
                                <strong style="font-size: 18px;">৳<?= number_format($stats['total_spent']); ?></strong>
                            </div>
                            <div style="text-align: center; padding: 10px; background: white; border-radius: var(--radius-sm);">
                                <i class="fas fa-star" style="color: #FD7E14; font-size: 20px;"></i>
                                <p style="margin: 5px 0 0 0; font-size: 14px;">Avg Vehicle Rating</p>
                                <strong style="font-size: 18px;"><?= number_format($stats['avg_vehicle_rating'], 1); ?> ⭐</strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <table style="width: 100%; margin-top: 20px;">
                        <tr>
                            <th style="width: 30%; padding: 12px; background: var(--off-white); color: var(--dark-gray); font-weight: 600;">Customer ID</th>
                            <td style="padding: 12px; color: var(--dark-gray);"><?= htmlspecialchars($customerInfo['customer_id']); ?></td>
                        </tr>
                        <tr>
                            <th style="padding: 12px; background: var(--off-white); color: var(--dark-gray); font-weight: 600;">Username</th>
                            <td style="padding: 12px; color: var(--dark-gray);"><?= htmlspecialchars($customerInfo['username']); ?></td>
                        </tr>
                        <tr>
                            <th style="padding: 12px; background: var(--off-white); color: var(--dark-gray); font-weight: 600;">Email</th>
                            <td style="padding: 12px; color: var(--dark-gray);"><?= htmlspecialchars($customerInfo['email']); ?></td>
                        </tr>
                        <tr>
                            <th style="padding: 12px; background: var(--off-white); color: var(--dark-gray); font-weight: 600;">Primary Phone</th>
                            <td style="padding: 12px; color: var(--dark-gray);"><?= htmlspecialchars($customerInfo['phone_1']); ?></td>
                        </tr>
                        <tr>
                            <th style="padding: 12px; background: var(--off-white); color: var(--dark-gray); font-weight: 600;">Secondary Phone</th>
                            <td style="padding: 12px; color: var(--dark-gray);"><?= $customerInfo['phone_2'] ? htmlspecialchars($customerInfo['phone_2']) : 'N/A'; ?></td>
                        </tr>
                        <tr>
                            <th style="padding: 12px; background: var(--off-white); color: var(--dark-gray); font-weight: 600;">NID Number</th>
                            <td style="padding: 12px; color: var(--dark-gray);"><?= $customerInfo['nid_number'] ? htmlspecialchars($customerInfo['nid_number']) : 'N/A'; ?></td>
                        </tr>
                        <tr>
                            <th style="padding: 12px; background: var(--off-white); color: var(--dark-gray); font-weight: 600;">Address</th>
                            <td style="padding: 12px; color: var(--dark-gray);"><?= $customerInfo['address'] ? htmlspecialchars($customerInfo['address']) : 'N/A'; ?></td>
                        </tr>
                        <tr>
                            <th style="padding: 12px; background: var(--off-white); color: var(--dark-gray); font-weight: 600;">Member Since</th>
                            <td style="padding: 12px; color: var(--dark-gray);"><?= date('F j, Y', strtotime($customerInfo['created_at'])); ?></td>
                        </tr>
                    </table>
                    
                    <hr style="margin: 30px 0;">
                    
                    <h4>Update Profile</h4>
                    <form method="POST" enctype="multipart/form-data" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($customerInfo['full_name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Primary Phone</label>
                            <input type="text" name="phone_1" class="form-control" value="<?= htmlspecialchars($customerInfo['phone_1']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Secondary Phone</label>
                            <input type="text" name="phone_2" class="form-control" value="<?= htmlspecialchars($customerInfo['phone_2']); ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($customerInfo['address']); ?>">
                        </div>
                        <div class="form-group" style="grid-column: span 2;">
                            <label class="form-label">Profile Image</label>
                            <input type="file" name="profile_image" class="form-control">
                        </div>
                        <div style="grid-column: span 2;">
                            <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
