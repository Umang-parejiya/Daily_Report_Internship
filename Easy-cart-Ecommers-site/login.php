<?php
// Login Page - login.php
$current_page = 'login';
$page_title = 'Easy-Cart - Login';

// Include header
include 'includes/header.php';
?>

<div class="container">
    <section class="section">
        <div class="section-header">
            <h1 class="section-title">Login</h1>
            <p class="section-subtitle">Welcome back! Please login to your account</p>
        </div>

        <div class="card" style="max-width: 500px; margin: 0 auto;">
            <form class="form-fieldset">
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-input" placeholder="Enter your email" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-input" placeholder="Enter your password" required>
                </div>

                <div class="form-group" style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" id="remember" style="width: auto;">
                    <label for="remember" style="margin: 0; font-weight: normal; text-transform: none;">
                        Remember me
                    </label>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    Login
                </button>

                <div style="text-align: center; margin-top: 1.5rem;">
                    <p style="color: var(--text-secondary);">
                        Don't have an account? 
                        <a href="signup.php" style="color: var(--accent); font-weight: 600;">Sign up</a>
                    </p>
                </div>
            </form>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>
