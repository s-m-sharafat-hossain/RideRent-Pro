<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/User.php';

/**
 * Auth Controller for handling authentication requests
 * 
 * @package RideRentPro\Controllers
 * @author RideRent Pro Team
 * @version 1.0.0
 */
class AuthController extends Controller {
    /**
     * User model instance
     * @var User
     */
    private $userModel;
    
    /**
     * Database instance
     * @var Database
     */
    private $db;
    
    /**
     * Constructor - Initialize user model and database
     */
    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
        $this->db = Database::getInstance();
    }
    
    /**
     * Handle user login
     * 
     * @return void
     */
    public function login() {
        if ($this->isLoggedIn()) {
            $role = $this->getUserRole();
            $this->redirect("/$role/dashboard");
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $this->sanitize($_POST['email']);
            $password = $this->sanitize($_POST['password']);
            $role = $this->sanitize($_POST['role']);
            
            $user = $this->userModel->authenticate($email, $password, $role);
            
            if ($user) {
                switch($role) {
                    case 'Admin':
                        $_SESSION['admin_id'] = $user['admin_id'];
                        $_SESSION['admin_name'] = $user['full_name'];
                        $_SESSION['role'] = 'admin';
                        $this->redirect('/admin/dashboard');
                        break;
                    case 'Vehicle Owner':
                        $_SESSION['owner_id'] = $user['owner_id'];
                        $_SESSION['owner_name'] = $user['full_name'];
                        $_SESSION['role'] = 'owner';
                        $this->redirect('/owner/dashboard');
                        break;
                    case 'Driver':
                        $_SESSION['driver_id'] = $user['driver_id'];
                        $_SESSION['driver_name'] = $user['full_name'];
                        $_SESSION['role'] = 'driver';
                        $this->redirect('/driver/dashboard');
                        break;
                    case 'Customer':
                        $_SESSION['customer_id'] = $user['customer_id'];
                        $_SESSION['customer_name'] = $user['full_name'];
                        $_SESSION['role'] = 'customer';
                        $this->redirect('/customer/dashboard');
                        break;
                }
            } else {
                $this->setFlash('error', 'Invalid email or password');
            }
        }
        
        $this->render('auth/login', [
            'error' => $this->getFlash()
        ]);
    }
    
    public function register() {
        if ($this->isLoggedIn()) {
            $role = $this->getUserRole();
            $this->redirect("/$role/dashboard");
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $role = $this->sanitize($_POST['role']);
            $username = $this->sanitize($_POST['username']);
            $email = $this->sanitize($_POST['email']);
            $password = $this->sanitize($_POST['password']);
            $phone = $this->sanitize($_POST['phone']);
            $full_name = $this->sanitize($_POST['full_name']);
            
            // Check if email or username already exists
            $check_table = '';
            switch($role) {
                case 'customer':
                    $check_table = 'customer';
                    break;
                case 'driver':
                    $check_table = 'driver';
                    break;
                case 'owner':
                    $check_table = 'vehicle_owner';
                    break;
                case 'admin':
                    $check_table = 'admin';
                    break;
                default:
                    $this->setFlash('error', 'Invalid role selected!');
                    $this->render('auth/register', ['error' => $this->getFlash()]);
                    return;
            }
            
            $db = Database::getInstance();
            $check_sql = "SELECT * FROM $check_table WHERE email='$email' OR username='$username'";
            $check_result = $db->query($check_sql);
            
            if ($check_result && $db->numRows($check_result) > 0) {
                $this->setFlash('error', 'Email or username already exists!');
                $this->render('auth/register', ['error' => $this->getFlash()]);
                return;
            }
            
            // Prepare data based on role
            $data = [
                'full_name' => $full_name,
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'phone' => $phone
            ];
            
            // Add role-specific fields
            if ($role === 'customer') {
                $data['phone_1'] = $phone;
                unset($data['phone']);
            }
            
            // Map lowercase role to proper format for User model
            $userModelRole = '';
            switch($role) {
                case 'customer':
                    $userModelRole = 'Customer';
                    break;
                case 'driver':
                    $userModelRole = 'Driver';
                    break;
                case 'owner':
                    $userModelRole = 'Vehicle Owner';
                    break;
                case 'admin':
                    $userModelRole = 'Admin';
                    break;
            }
            
            $userId = $this->userModel->register($data, $userModelRole);
            
            if ($userId) {
                // Set session based on role
                switch($role) {
                    case 'customer':
                        $_SESSION['customer_id'] = $userId;
                        $_SESSION['customer_name'] = $full_name;
                        $_SESSION['role'] = 'customer';
                        $this->redirect('/customer/dashboard');
                        break;
                    case 'driver':
                        $_SESSION['driver_id'] = $userId;
                        $_SESSION['driver_name'] = $full_name;
                        $_SESSION['role'] = 'driver';
                        $this->redirect('/driver/dashboard');
                        break;
                    case 'owner':
                        $_SESSION['owner_id'] = $userId;
                        $_SESSION['owner_name'] = $full_name;
                        $_SESSION['role'] = 'owner';
                        $this->redirect('/owner/dashboard');
                        break;
                    case 'admin':
                        $_SESSION['admin_id'] = $userId;
                        $_SESSION['admin_name'] = $full_name;
                        $_SESSION['role'] = 'admin';
                        $this->redirect('/admin/dashboard');
                        break;
                }
            } else {
                $this->setFlash('error', 'Registration failed. Please try again.');
            }
        }
        
        $this->render('auth/register', [
            'error' => $this->getFlash()
        ]);
    }
    
    public function logout() {
        session_destroy();
        $this->redirect('/');
    }
    
    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $this->sanitize($_POST['email']);
            
            // Check if email exists in any user table
            $check_admin = $this->db->query("SELECT * FROM admin WHERE email = '$email'");
            $check_customer = $this->db->query("SELECT * FROM customer WHERE email = '$email'");
            $check_driver = $this->db->query("SELECT * FROM driver WHERE email = '$email'");
            $check_owner = $this->db->query("SELECT * FROM vehicle_owner WHERE email = '$email'");
            
            $user_found = false;
            $user_type = '';
            
            if ($check_admin && $this->db->numRows($check_admin) > 0) {
                $user_found = true;
                $user_type = 'admin';
            } elseif ($check_customer && $this->db->numRows($check_customer) > 0) {
                $user_found = true;
                $user_type = 'customer';
            } elseif ($check_driver && $this->db->numRows($check_driver) > 0) {
                $user_found = true;
                $user_type = 'driver';
            } elseif ($check_owner && $this->db->numRows($check_owner) > 0) {
                $user_found = true;
                $user_type = 'owner';
            }
            
            if ($user_found) {
                // Generate random password
                $new_password = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%"), 0, 10);
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                
                // Update password in the appropriate table
                switch($user_type) {
                    case 'admin':
                        $this->db->query("UPDATE admin SET password = '$hashed_password' WHERE email = '$email'");
                        break;
                    case 'customer':
                        $this->db->query("UPDATE customer SET password = '$hashed_password' WHERE email = '$email'");
                        break;
                    case 'driver':
                        $this->db->query("UPDATE driver SET password = '$hashed_password' WHERE email = '$email'");
                        break;
                    case 'owner':
                        $this->db->query("UPDATE vehicle_owner SET password = '$hashed_password' WHERE email = '$email'");
                        break;
                }
                
                $this->setFlash('success', "Password reset successful! Your new password is: <strong>$new_password</strong>");
            } else {
                $this->setFlash('error', 'Email not found in our system!');
            }
        }
        
        $this->render('auth/forgot_password', [
            'message' => $this->getFlash()
        ]);
    }
}
?>