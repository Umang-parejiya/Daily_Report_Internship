/**
 * validation.js
 * Handles client-side form validation for Login, Signup, and Checkout interfaces.
 * Follows modern JavaScript practices and avoids inline scripts.
 */

document.addEventListener('DOMContentLoaded', function () {

    // --- 1. LOGIN FORM VALIDATION ---
    const loginForm = document.querySelector('.auth-form');
    // Ensure we are on the login page by checking for unique elements if class is reused
    const isLoginPage = document.querySelector('h2.auth-title') && document.querySelector('h2.auth-title').textContent.includes('Sign In');

    if (loginForm && isLoginPage) {
        loginForm.addEventListener('submit', function (e) {
            const emailInput = loginForm.querySelector('input[type="email"]');
            const passwordInput = loginForm.querySelector('input[type="password"]');
            let isValid = true;

            // Simple reset of previous errors (assuming .error-msg usage or similar, 
            // but for now relying on alerts/native validation as per previous logic, 
            // expanding to custom UI feedback if structure permits.)
            // Requirement says "Show error messages below fields", "Add/remove error CSS classes"

            // Helpers
            const showError = (input, msg) => {
                const parent = input.parentElement.parentElement; // form-group
                // Remove existing error if any
                const existing = parent.querySelector('.error-message');
                if (existing) existing.remove();

                const error = document.createElement('small');
                error.className = 'error-message';
                error.style.color = '#ef4444';
                error.style.fontSize = '0.875rem';
                error.style.marginTop = '0.25rem';
                error.textContent = msg;

                input.classList.add('error');
                parent.appendChild(error);
            };

            const clearError = (input) => {
                const parent = input.parentElement.parentElement;
                const existing = parent.querySelector('.error-message');
                if (existing) existing.remove();
                input.classList.remove('error');
            };

            // Email Logic
            clearError(emailInput);
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailInput.value.trim()) {
                showError(emailInput, 'Email is required');
                isValid = false;
            } else if (!emailRegex.test(emailInput.value.trim())) {
                showError(emailInput, 'Please enter a valid email address');
                isValid = false;
            }

            // Password Logic
            clearError(passwordInput);
            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

            if (!passwordInput.value) {
                showError(passwordInput, 'Password is required');
                isValid = false;
            } else if (!passwordRegex.test(passwordInput.value)) {
                showError(passwordInput, 'Password must contain 8+ chars, Uppercase, Lowercase, Number & Special Char');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            } else {
                // AJAX Login
                e.preventDefault();
                const submitBtn = loginForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;
                submitBtn.textContent = 'Signing In...';
                submitBtn.disabled = true;

                const formData = new FormData();
                formData.append('email', emailInput.value);
                formData.append('password', passwordInput.value);
                formData.append('ajax_login', '1');

                fetch('login.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Redirect to home or intended page
                            window.location.href = 'index.php';
                        } else {
                            showError(passwordInput, data.message || 'Invalid email or password');
                        }
                    })
                    .catch(err => {
                        console.error('Login error:', err);
                        showError(passwordInput, 'An error occurred. Please try again.');
                    })
                    .finally(() => {
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                    });
            }
        });
    }


    // --- 2. SIGNUP FORM VALIDATION ---
    const signupForm = document.querySelector('.auth-form');
    const isSignupPage = document.querySelector('h2.auth-title') && document.querySelector('h2.auth-title').textContent.includes('Create Account');

    if (signupForm && isSignupPage) {
        signupForm.addEventListener('submit', function (e) {
            let isValid = true;

            const nameInputs = signupForm.querySelectorAll('input[type="text"]');
            const emailInput = signupForm.querySelector('input[type="email"]');
            const passwordInput = signupForm.querySelector('input[placeholder*="strong password"]');
            const confirmInput = signupForm.querySelector('input[placeholder*="Confirm"]');
            const termsBox = signupForm.querySelector('input[type="checkbox"]');

            const showError = (input, msg) => {
                // Adjust for different DOM structure in signup if needed, but looks similar
                // form-group -> input-wrapper -> input. 
                // Actually logic above used parentElement.parentElement which is form-group.
                const parent = input.closest('.form-group') || input.parentElement;
                const existing = parent.querySelector('.error-message');
                if (existing) existing.remove();

                const error = document.createElement('small');
                error.className = 'error-message';
                error.style.color = '#ef4444';
                error.style.fontSize = '0.875rem';
                error.style.marginTop = '0.25rem';
                error.style.display = 'block';
                error.textContent = msg;

                input.classList.add('error');
                parent.appendChild(error);
            };

            const clearError = (input) => {
                const parent = input.closest('.form-group') || input.parentElement;
                if (!parent) return;
                const existing = parent.querySelector('.error-message');
                if (existing) existing.remove();
                input.classList.remove('error');
            };

            // Name Validation
            nameInputs.forEach(input => {
                clearError(input);
                if (!input.value.trim()) {
                    showError(input, 'This field is required');
                    isValid = false;
                }
            });

            // Email Validation
            clearError(emailInput);
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailInput.value.trim()) {
                showError(emailInput, 'Email is required');
                isValid = false;
            } else if (!emailRegex.test(emailInput.value.trim())) {
                showError(emailInput, 'Invalid email format');
                isValid = false;
            }

            // Password Validation
            clearError(passwordInput);
            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

            if (!passwordInput.value) {
                showError(passwordInput, 'Password is required');
                isValid = false;
            } else if (!passwordRegex.test(passwordInput.value)) {
                showError(passwordInput, 'Password must contain 8+ chars, Uppercase, Lowercase, Number & Special Char');
                isValid = false;
            }

            // Confirm Password
            clearError(confirmInput);
            if (confirmInput.value !== passwordInput.value) {
                showError(confirmInput, 'Passwords do not match');
                isValid = false;
            }

            // Terms
            // Usually terms have a specific label structure
            const termsLabel = termsBox.closest('.checkbox-label');
            if (!termsBox.checked) {
                termsLabel.style.color = '#ef4444';
                isValid = false;
            } else {
                termsLabel.style.color = '';
            }

            if (!isValid) {
                e.preventDefault();
            } else {
                // AJAX Signup
                e.preventDefault();
                const submitBtn = signupForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;
                submitBtn.textContent = 'Creating Account...';
                submitBtn.disabled = true;

                const formData = new FormData();
                formData.append('email', emailInput.value);
                formData.append('password', passwordInput.value);
                formData.append('first_name', nameInputs[0].value);
                formData.append('last_name', nameInputs[1].value);
                formData.append('ajax_signup', '1');

                fetch('signup.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Redirect to home or login
                            window.location.href = 'index.php';
                        } else {
                            showError(emailInput, data.message || 'Error occurred during signup');
                        }
                    })
                    .catch(err => {
                        console.error('Signup error:', err);
                        showError(emailInput, 'An error occurred. Please try again.');
                    })
                    .finally(() => {
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                    });
            }
        });
    }

});

// Helper for Password Toggle (Shared)
document.addEventListener('click', (e) => {
    if (e.target.closest('.password-toggle')) {
        const btn = e.target.closest('.password-toggle');
        const input = btn.previousElementSibling;
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);

        // Update Icon (Simplified for brevity, can use the SVGs from original)
        // We can toggle a class on the button to switch cleaner CSS icons if available, 
        // or just swap HTML.
        const svg = btn.querySelector('svg');
        if (type === 'text') {
            svg.style.opacity = '0.5'; // Visual feedback
        } else {
            svg.style.opacity = '1';
        }
    }
});
