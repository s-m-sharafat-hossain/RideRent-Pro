<?php
/**
 * Customer Browse Vehicles View
 * 
 * This view displays available vehicles for customers to browse and book.
 * It includes search and filter functionality to find vehicles by type and location.
 * 
 * @package RideRentPro\Views\Customer
 * @author RideRent Pro Team
 * @version 1.0.0
 * 
 * @var array $vehicles Array of available vehicles with ratings and review counts
 * @var string $search Current search term applied to vehicle list
 * @var string $filter_type Current vehicle type filter applied
 * @var string $userName The name of the current customer
 */
$pageTitle = 'Browse Vehicles';
require_once __DIR__ . '/../layouts/main.php';
?>

<!-- Dashboard Container -->
<div class="dashboard-container">
    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-car"></i> Browse Vehicles</h1>
            <p>Find and rent the perfect vehicle for your needs</p>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Search & Filter</h3>
            </div>
            <div class="card-body">
                <form method="GET" action="/customer/vehicles" style="display: grid; grid-template-columns: 1fr 1fr auto auto; gap: 15px; align-items: end;">
                    <div class="form-group" style="margin: 0;">
                        <input type="text" name="search" class="form-control" placeholder="Search vehicles..." value="<?= htmlspecialchars($search); ?>">
                    </div>
                    <div class="form-group" style="margin: 0;">
                        <select name="type" class="form-select">
                            <option value="">All Types</option>
                            <option value="Sedan" <?= $filter_type == 'Sedan' ? 'selected' : ''; ?>>Sedan</option>
                            <option value="SUV" <?= $filter_type == 'SUV' ? 'selected' : ''; ?>>SUV</option>
                            <option value="Microbus" <?= $filter_type == 'Microbus' ? 'selected' : ''; ?>>Microbus</option>
                            <option value="Van" <?= $filter_type == 'Van' ? 'selected' : ''; ?>>Van</option>
                            <option value="Bike" <?= $filter_type == 'Bike' ? 'selected' : ''; ?>>Bike</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                    <a href="/customer/vehicles" class="btn btn-secondary"><i class="fas fa-redo"></i> Reset</a>
                </form>
            </div>
        </div>

        <div class="vehicle-grid">
            <?php if(!empty($vehicles)): ?>
                <?php foreach($vehicles as $row): ?>
                    <div class="vehicle-card">
                        <?php if(!empty($row['image'])): ?>
                            <img src="/public/assets/uploads/<?= htmlspecialchars($row['image']); ?>" alt="<?= htmlspecialchars($row['vehicle_name']); ?>">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/300x200?text=No+Image" alt="<?= htmlspecialchars($row['vehicle_name']); ?>">
                        <?php endif; ?>
                        <div class="vehicle-info">
                            <h4><?= htmlspecialchars($row['vehicle_name']); ?></h4>
                            <p>
                                <strong><?= htmlspecialchars($row['brand']); ?> <?= htmlspecialchars($row['model']); ?></strong> (<?= htmlspecialchars($row['year']); ?>)<br>
                                <?= htmlspecialchars($row['vehicle_type']); ?> • <?= htmlspecialchars($row['seat_capacity']); ?> Seats • <?= htmlspecialchars($row['transmission']); ?><br>
                                <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($row['location']); ?>
                            </p>
                            <?php if($row['avg_rating'] && $row['review_count'] > 0): ?>
                                <div style="margin: 10px 0;">
                                    <span style="color: #FD7E14;"><?= number_format($row['avg_rating'], 1); ?> &#9733;</span>
                                    <small style="color: var(--medium-gray);">(<?= $row['review_count']; ?> reviews)</small>
                                </div>
                            <?php endif; ?>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin: 15px 0;">
                                <span style="color: var(--accent-pink); font-size: 24px; font-weight: 700;">৳<?= htmlspecialchars($row['price_per_day']); ?>/day</span>
                                <span class="badge badge-success">Available</span>
                            </div>
                            <div style="display: flex; gap: 10px;">
                                <a href="/customer/book-vehicle?id=<?= $row['vehicle_id']; ?>" class="btn btn-primary" style="flex: 1;">Book Now</a>
                                <a href="/customer/compare?add=<?= $row['vehicle_id']; ?>" class="btn btn-secondary"><i class="fas fa-balance-scale"></i></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class='no-data'><h4>No vehicles found matching your criteria.</h4></div>
            <?php endif; ?>
        </div>
    </div>
</div>