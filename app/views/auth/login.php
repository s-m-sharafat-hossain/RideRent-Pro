<?php
/**
 * Login View
 * 
 * This view displays the login form for user authentication.
 * Users can log in as Admin, Vehicle Owner, Driver, or Customer.
 * 
 * @package RideRentPro\Views\Auth
 * @author RideRent Pro Team
 * @version 1.0.0
 * 
 * @var array $error Optional error message array with 'type' and 'message' keys
 */
$pageTitle = 'Login';
require_once __DIR__ . '/../layouts/main.php';
?>

<div style="background: var(--gradient-primary); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px;">

<div class="form-container" style="max-width: 500px;">
    <div style="text-align: center; margin-bottom: 30px;">
        <h1 style="font-size: 32px; margin-bottom: 10px;">
            <i class="fas fa-car-side" style="background: var(--gradient-primary); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;"></i>
        </h1>
        <h2 class="form-title">Welcome Back</h2>
        <p class="form-subtitle">Sign in to your RideRent Pro account</p>
        <button class="theme-toggle" onclick="toggleTheme()" style="margin-top: 20px;">
            <i class="fas fa-moon"></i>
            <span>Dark Mode</span>
        </button>
    </div>

    <?php if(isset($error) && $error): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error['message']) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= APP_BASE_URL ?>/auth/login">
        <div class="form-group">
            <label class="form-label"><i class="fas fa-envelope"></i> Email Address</label>
            <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label class="form-label"><i class="fas fa-lock"></i> Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
            </div>

            <div class="form-group">
                <label class="form-label"><i class="fas fa-user-tie"></i> Login As</label>
                <select name="role" class="form-select" required>
                    <option value="">Select Role</option>
                    <option value="Admin">Admin</option>
                    <option value="Vehicle Owner">Vehicle Owner</option>
                    <option value="Driver">Driver</option>
                    <option value="Customer">Customer</option>
                </select>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-block">
            <i class="fas fa-sign-in-alt"></i> Login
        </button>
    </form>

    <div style="text-align: center; margin-top: 25px;">
        <p style="color: var(--medium-gray); margin-bottom: 10px;">
            <a href="<?= APP_BASE_URL ?>/auth/forgot-password" style="color: var(--primary); text-decoration: none;">Forgot Password?</a>
        </p>
        <p style="color: var(--medium-gray);">
            Don't have an account? 
            <a href="<?= APP_BASE_URL ?>/auth/register" style="color: var(--maya-blue); text-decoration: none; font-weight: 600;">Register</a>
        </p>
    </div>
</div>

</div>