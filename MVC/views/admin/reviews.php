<?php
/**
 * Admin Reviews Management View
 * 
 * This view displays the review management interface for administrators.
 * It allows admins to approve, reject, or delete reviews submitted by customers.
 * 
 * @package RideRentPro\Views\Admin
 * @author RideRent Pro Team
 * @version 1.0.0
 * 
 * @var array $reviews Array of all review records with customer information
 * @var string $userName The name of the current admin user
 */
$pageTitle = 'Reviews Management';
require_once __DIR__ . '/../layouts/main.php';
?>

<!-- Dashboard Container -->
<div class="dashboard-container">
    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-star"></i> Reviews Management</h1>
            <p>Manage customer reviews and feedback</p>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Target Type</th>
                                <th>Target ID</th>
                                <th>Rating</th>
                                <th>Comment</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($reviews)): ?>
                                <?php foreach($reviews as $row): ?>
                                    <?php
                                    $statusClass = '';
                                    if($row['status'] == 'approved') $statusClass = 'badge-success';
                                    elseif($row['status'] == 'pending') $statusClass = 'badge-warning';
                                    else $statusClass = 'badge-danger';
                                    
                                    $stars = '';
                                    for($i = 1; $i <= 5; $i++) {
                                        $stars .= $i <= $row['rating'] ? '⭐' : '☆';
                                    }
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['review_id']); ?></td>
                                        <td><?= htmlspecialchars($row['customer_name']); ?></td>
                                        <td><?= ucfirst($row['target_type']); ?></td>
                                        <td><?= htmlspecialchars($row['target_id']); ?></td>
                                        <td><?= $stars; ?></td>
                                        <td><?= htmlspecialchars(substr($row['comment'], 0, 50)) . (strlen($row['comment']) > 50 ? '...' : ''); ?></td>
                                        <td><span class="badge <?= $statusClass; ?>"><?= ucfirst($row['status']); ?></span></td>
                                        <td><?= date('M d, Y', strtotime($row['created_at'])); ?></td>
                                        <td>
                                            <?php if($row['status'] == 'pending'): ?>
                                                <a href="<?= APP_BASE_URL ?>/admin/reviews?action=approve&id=<?= $row['review_id']; ?>" class="btn btn-sm btn-success"><i class="fas fa-check"></i></a>
                                                <a href="<?= APP_BASE_URL ?>/admin/reviews?action=reject&id=<?= $row['review_id']; ?>" class="btn btn-sm btn-danger"><i class="fas fa-times"></i></a>
                                            <?php endif; ?>
                                            <a href="<?= APP_BASE_URL ?>/admin/reviews?delete=<?= $row['review_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" style="text-align: center;">No reviews found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>