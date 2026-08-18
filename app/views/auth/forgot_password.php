<?php
/**
 * Forgot Password View
 * 
 * This view displays the password reset form for users who have forgotten their password.
 * Users can enter their email address to receive a new password.
 * 
 * @package RideRentPro\Views\Auth
 * @author RideRent Pro Team
 * @version 1.0.0
 * 
 * @var array $message Optional message array with 'type' and 'message' keys
 */
$pageTitle = 'Forgot Password';
require_once __DIR__ . '/../layouts/main.php';
?>

<div style="background: var(--gradient-primary); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px;">

<div class="form-container" style="max-width: 500px;">
    <div style="text-align: center; margin-bottom: 30px;">
        <h1 style="font-size: 32px; margin-bottom: 10px;">
            <i class="fas fa-key" style="background: var(--gradient-primary); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;"></i>
        </h1>
        <h2 class="form-title">Forgot Password</h2>
        <p class="form-subtitle">Reset your password</p>
        <button class="theme-toggle" onclick="toggleTheme()" style="margin-top: 20px;">
            <i class="fas fa-moon"></i>
            <span>Dark Mode</span>
        </button>
    </div>

    <?php if(isset($message) && $message): ?>
        <div class="alert alert-<?php echo $message['type'] ?? 'danger'; ?>">
            <i class="fas fa-<?php echo ($message['type'] ?? 'danger') == 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i> 
            <?= $message['message'] ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= APP_BASE_URL ?>/auth/forgot-password">
        <div class="form-group">
            <label class="form-label"><i class="fas fa-envelope"></i> Email Address</label>
            <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
        </div>

        <button type="submit" name="reset_password" class="btn btn-primary btn-block">
            <i class="fas fa-key"></i> Reset Password
        </button>
    </form>

    <div style="text-align: center; margin-top: 25px;">
        <p style="color: var(--medium-gray);">
            Remember your password? 
            <a href="<?= APP_BASE_URL ?>/auth/login" style="color: var(--primary); text-decoration: none; font-weight:600;">Login here</a>
        </p>
    </div>
</div>

</div>