<?php
/**
 * Driver Performance View
 * 
 * This view displays performance metrics and analytics for drivers.
 * It shows completion rates, booking statistics, earnings, reviews, and monthly performance charts.
 * 
 * @package RideRentPro\Views\Driver
 * @author RideRent Pro Team
 * @version 1.0.0
 * 
 * @var array $driverInfo Driver personal information and profile data
 * @var int $total_bookings Total number of bookings
 * @var int $completed_bookings Number of completed bookings
 * @var int $cancelled_bookings Number of cancelled bookings
 * @var float $total_earnings Total earnings from completed bookings
 * @var float $completion_rate Percentage of completed bookings
 * @var array $reviews Array of recent reviews from customers
 * @var array $monthly_earnings Array of monthly earnings data for the last 6 months
 * @var string $userName The name of the current driver
 */
$pageTitle = 'My Performance';
require_once __DIR__ . '/../layouts/main.php';
?>

<!-- Dashboard Container -->
<div class="dashboard-container">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-chart-line"></i> My Performance</h1>
            <p>Track your performance and earnings</p>
        </div>

        <!-- Performance Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #2F80ED, #1A5BB5);">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <h3>Total Bookings</h3>
                    <p><?= $total_bookings; ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #00C9A7, #009B80);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>Completed</h3>
                    <p><?= $completed_bookings; ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #E74C3C, #C0392B);">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>Cancelled</h3>
                    <p><?= $cancelled_bookings; ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #6C5CE7, #5B4CE6);">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-content">
                    <h3>Total Earnings</h3>
                    <p>৳<?= number_format($total_earnings, 2); ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #FD7E14, #E67E22);">
                    <i class="fas fa-percentage"></i>
                </div>
                <div class="stat-content">
                    <h3>Completion Rate</h3>
                    <p><?= $completion_rate; ?>%</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #E84393, #D63031);">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-content">
                    <h3>Rating</h3>
                    <p><?= number_format($driverInfo['rating'], 1); ?> ⭐ (<?= $driverInfo['rating_count']; ?>)</p>
                </div>
            </div>
        </div>

        <!-- Monthly Earnings Chart -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Monthly Earnings (Last 6 Months)</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; align-items: flex-end; gap: 20px; height: 300px; padding: 20px;">
                    <?php
                    $max_earnings = max(array_column($monthly_earnings, 'earnings'));
                    if($max_earnings == 0) $max_earnings = 1;
                    
                    foreach($monthly_earnings as $data) {
                        $height = ($data['earnings'] / $max_earnings) * 250;
                        $color = $data['earnings'] > 0 ? 'var(--primary)' : 'var(--medium-gray)';
                    ?>
                    <div style="flex: 1; display: flex; flex-direction: column; align-items: center;">
                        <div style="width: 50px; height: <?= $height; ?>px; background: <?= $color; ?>; border-radius: 5px 5px 0 0; transition: height 0.3s;"></div>
                        <p style="margin-top: 10px; font-weight: 600;"><?= $data['month']; ?></p>
                        <p style="font-size: 14px; color: var(--medium-gray);">৳<?= number_format($data['earnings']); ?></p>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
            <!-- Recent Reviews -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Reviews</h3>
                </div>
                <div class="card-body">
                    <?php if(!empty($reviews)): ?>
                        <?php foreach($reviews as $review): ?>
                            <?php
                            $stars = '';
                            for($i = 1; $i <= 5; $i++) {
                                $stars .= $i <= $review['rating'] ? '⭐' : '☆';
                            }
                            ?>
                            <div style="padding: 15px; border-bottom: 1px solid var(--border-color);">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                    <strong><?= htmlspecialchars($review['customer_name']); ?></strong>
                                    <span><?= $stars; ?></span>
                                </div>
                                <p style="color: var(--medium-gray); font-size: 14px;"><?= htmlspecialchars($review['comment']); ?></p>
                                <small style="color: var(--light-gray);"><?= date('M d, Y', strtotime($review['created_at'])); ?></small>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style='text-align: center; color: var(--medium-gray); padding: 20px;'>No reviews yet.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Performance Tips -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Performance Tips</h3>
                </div>
                <div class="card-body">
                    <div style="display: flex; flex-direction: column; gap: 15px;">
                        <div style="display: flex; gap: 15px; align-items: start;">
                            <i class="fas fa-lightbulb" style="color: #FD7E14; font-size: 20px; margin-top: 3px;"></i>
                            <div>
                                <strong>Maintain High Availability</strong>
                                <p style="color: var(--medium-gray); font-size: 14px;">Keep your availability status updated to receive more booking opportunities.</p>
                            </div>
                        </div>
                        <div style="display: flex; gap: 15px; align-items: start;">
                            <i class="fas fa-clock" style="color: #2F80ED; font-size: 20px; margin-top: 3px;"></i>
                            <div>
                                <strong>Be Punctual</strong>
                                <p style="color: var(--medium-gray); font-size: 14px;">Arrive on time for pickups to maintain good customer ratings.</p>
                            </div>
                        </div>
                        <div style="display: flex; gap: 15px; align-items: start;">
                            <i class="fas fa-car" style="color: #00C9A7; font-size: 20px; margin-top: 3px;"></i>
                            <div>
                                <strong>Keep Vehicle Clean</strong>
                                <p style="color: var(--medium-gray); font-size: 14px;">Maintain cleanliness of assigned vehicles for better customer experience.</p>
                            </div>
                        </div>
                        <div style="display: flex; gap: 15px; align-items: start;">
                            <i class="fas fa-smile" style="color: #E84393; font-size: 20px; margin-top: 3px;"></i>
                            <div>
                                <strong>Provide Excellent Service</strong>
                                <p style="color: var(--medium-gray); font-size: 14px;">Friendly behavior and professional attitude lead to better tips and ratings.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
