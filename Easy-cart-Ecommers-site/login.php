<?php
session_start();

// Handle AJAX Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_login'])) {
    header('Content-Type: application/json');
    
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Mock Validation: Accept any valid email with a non-empty password
    if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($password)) {
        $_SESSION['user_id'] = 1; // Mock user ID
        $_SESSION['user_email'] = $email;
        $_SESSION['logged_in'] = true;
        
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
    }
    exit;
}

// Login Page - login.php
$current_page = 'login';
$page_title = 'Easy-Cart - Login';

// Include header
include 'includes/header.php';
?>

<!-- Auth Container -->
<div class="auth-container">
    <div class="auth-layout">
        <!-- Left Side - Branding -->
        <div class="auth-sidebar">
            <div class="auth-branding">
                <div class="auth-logo">
                    <span class="logo-text">Easy-Cart</span>
                </div>
                <h1 class="auth-heading">Welcome Back!</h1>
                <p class="auth-subheading">Continue your shopping journey with us</p>

                <div class="auth-features">
                    <div class="feature-item">
                        <div class="feature-text">
                            <strong>Fast Delivery</strong>
                            <span>Get your orders delivered quickly</span>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-text">
                            <strong>Secure Shopping</strong>
                            <span>Your data is always protected</span>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-text">
                            <strong>Easy Payments</strong>
                            <span>Multiple payment options</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="auth-main">
            <div class="auth-card">
                <div class="auth-header">
                    <h2 class="auth-title">Sign In</h2>
                    <p class="auth-subtitle">Enter your credentials to access your account</p>
                </div>

                <!-- Social Login Buttons -->
                <div class="social-login">
                    <button type="button" class="social-btn google-btn">
                        <svg class="social-icon" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Continue with Google
                    </button>

                    <button type="button" class="social-btn facebook-btn">
                        <svg class="social-icon" viewBox="0 0 24 24">
                            <path fill="#1877F2" d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                        Continue with Facebook
                    </button>
                </div>

                <!-- Divider -->
                <div class="auth-divider">
                    <span>or continue with email</span>
                </div>

                <!-- Login Form -->
                <form class="auth-form">
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <div class="input-wrapper">
                            <input type="email" class="form-input" placeholder="Enter your email address" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="input-wrapper">
                            <input type="password" class="form-input" placeholder="Enter your password" required>
                            <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                                <svg class="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="checkbox-label">
                            <input type="checkbox" class="checkbox-input">
                            <span class="checkmark"></span>
                            Remember me
                        </label>

                        <a href="#" class="forgot-password">Forgot password?</a>
                    </div>

                    <button type="submit" class="auth-submit-btn">
                        Sign In
                    </button>
                </form>

                <!-- Sign Up Link -->
                <div class="auth-footer">
                    <p>Don't have an account?
                        <a href="signup.php" class="auth-link">Create one</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>


