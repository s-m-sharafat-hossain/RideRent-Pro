<?php
/**
 * Register View
 * 
 * This view displays the registration form for new user accounts.
 * Users can register as Customer, Driver, Vehicle Owner, or Admin.
 * 
 * @package RideRentPro\Views\Auth
 * @author RideRent Pro Team
 * @version 1.0.0
 * 
 * @var array $error Optional error message array with 'type' and 'message' keys
 */
$pageTitle = 'Register';
require_once __DIR__ . '/../layouts/main.php';
?>

<div style="background: var(--gradient-primary); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px;">

<div class="form-container" style="max-width: 600px;">
    <div style="text-align: center; margin-bottom: 30px;">
        <h1 style="font-size: 32px; margin-bottom: 10px;">
            <i class="fas fa-car-side" style="background: var(--gradient-primary); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;"></i>
        </h1>
        <h2 class="form-title">Create Account</h2>
        <p class="form-subtitle">Join RideRent Pro today</p>
        <button class="theme-toggle" onclick="toggleTheme()" style="margin-top: 20px;">
            <i class="fas fa-moon"></i>
            <span>Dark Mode</span>
        </button>
    </div>

    <?php if(isset($error) && $error): ?>
        <div class="alert alert-<?php echo $error['type'] ?? 'danger'; ?>">
            <i class="fas fa-<?php echo ($error['type'] ?? 'danger') == 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i> 
            <?= htmlspecialchars($error['message']) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= APP_BASE_URL ?>/auth/register">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label class="form-label"><i class="fas fa-user"></i> Full Name</label>
                <input type="text" name="full_name" class="form-control" placeholder="Enter your full name" required>
            </div>

            <div class="form-group">
                <label class="form-label"><i class="fas fa-at"></i> Username</label>
                <input type="text" name="username" class="form-control" placeholder="Choose a username" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label"><i class="fas fa-envelope"></i> Email Address</label>
            <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
        </div>

        <div class="form-group">
            <label class="form-label"><i class="fas fa-phone"></i> Phone Number</label>
            <input type="tel" name="phone" class="form-control" placeholder="Enter your phone number" required>
        </div>

        <div class="form-group">
            <label class="form-label"><i class="fas fa-lock"></i> Password</label>
            <input type="password" name="password" class="form-control" placeholder="Create a password" required>
        </div>

        <div class="form-group">
            <label class="form-label"><i class="fas fa-user-tie"></i> Register As</label>
            <select name="role" class="form-select" required>
                <option value="">Select Role</option>
                <option value="customer">Customer</option>
                <option value="driver">Driver</option>
                <option value="owner">Vehicle Owner</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <button type="submit" name="register" class="btn btn-primary btn-block">
            <i class="fas fa-user-plus"></i> Register
        </button>
    </form>

    <div style="text-align: center; margin-top: 25px;">
        <p style="color: var(--medium-gray);">
            Already have an account? 
            <a href="<?= APP_BASE_URL ?>/auth/login" style="color: var(--maya-blue); text-decoration: none; font-weight: 600;">Login here</a>
        </p>
    </div>
</div>

</div>