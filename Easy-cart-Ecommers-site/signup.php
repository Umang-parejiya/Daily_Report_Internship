<?php
// Signup Page - signup.php
$current_page = 'login';
$page_title = 'Easy-Cart - Sign Up';

// Include header
include 'includes/header.php';
?>

<div class="container">
    <section class="section">
        <div class="section-header">
            <h1 class="section-title">Create Account</h1>
            <p class="section-subtitle">Join Easy-Cart today and start shopping!</p>
        </div>

        <div class="card" style="max-width: 500px; margin: 0 auto;">
            <form class="form-fieldset">
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-input" placeholder="Enter your full name" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-input" placeholder="Enter your email" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-input" placeholder="Create a password" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" class="form-input" placeholder="Confirm your password" required>
                </div>

                <div class="form-group" style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" id="terms" style="width: auto;" required>
                    <label for="terms" style="margin: 0; font-weight: normal; text-transform: none;">
                        I agree to the Terms and Conditions
                    </label>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    Create Account
                </button>

                <div style="text-align: center; margin-top: 1.5rem;">
                    <p style="color: var(--text-secondary);">
                        Already have an account? 
                        <a href="login.php" style="color: var(--accent); font-weight: 600;">Login</a>
                    </p>
                </div>
            </form>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>
