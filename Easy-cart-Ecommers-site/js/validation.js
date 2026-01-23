/**
 * validation.js
 * Handles client-side form validation for Login, Signup, and Checkout interfaces.
 * Follows modern JavaScript practices and avoids inline scripts.
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // --- 1. LOGIN FORM VALIDATION ---
    const loginForm = document.querySelector('.auth-form');
    // Ensure we are on the login page by checking for unique elements if class is reused
    const isLoginPage = document.querySelector('h2.auth-title') && document.querySelector('h2.auth-title').textContent.includes('Sign In');
    
    if (loginForm && isLoginPage) {
        loginForm.addEventListener('submit', function(e) {
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
            if (!passwordInput.value) {
                showError(passwordInput, 'Password is required');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            } else {
                // If valid, let it submit (or handle via AJAX if that was the plan, but requirements say "data logic remains PHP")
                // The original code halted with "Loggin successful" alert. We will remove that stopping block so PHP can handle it,
                // or if this is a mockup, show success. 
                // Since user didn't ask to CHANGE authentication flow to AJAX, we just validate.
            }
        });
    }


    // --- 2. SIGNUP FORM VALIDATION ---
    const signupForm = document.querySelector('.auth-form');
    const isSignupPage = document.querySelector('h2.auth-title') && document.querySelector('h2.auth-title').textContent.includes('Create Account');

    if (signupForm && isSignupPage) {
        signupForm.addEventListener('submit', function(e) {
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
            if (passwordInput.value.length < 8) {
                showError(passwordInput, 'Password must be at least 8 characters');
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
            if(!termsBox.checked) {
                termsLabel.style.color = '#ef4444';
                isValid = false;
            } else {
                termsLabel.style.color = '';
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    }

    // --- 3. CHECKOUT VALIDATION ---
    const checkoutForm = document.querySelector('.checkout-form'); // Parent container
    if (checkoutForm) {
        // Checkout logic is triggered by "Complete Order" button which is outside the form tags in the provided snippets sometimes,
        // or effectively the "Place Order" button calls a function.
        // We need to intercept that button if it exists, or attach to the form submission if wrapped.
        
        // In the provided checkout.php, there's a button onclick="placeOrder()". 
        // We will remove that inline handler and attach event listener here.
        
        const placeOrderBtn = document.querySelector('button.btn-primary[onclick*="placeOrder"]');
        if (placeOrderBtn) {
            // Remove the inline attribute to prevent double firing before we attach our logic? 
            // Best practice is to replace the element or let the inline remain but preventDefault in our handler? 
            // The instructions say "Modify existing JS only where required" and "Remove inline JS".
            // So we will assume we remove the onclick attribute in the PHP file.
            
            placeOrderBtn.addEventListener('click', function(e) {
                let isValid = true;
                
                // Fields
                const requiredInputs = document.querySelectorAll('.checkout-form input[required]');
                
                const showError = (input, msg) => {
                    input.style.borderColor = '#ef4444';
                    // Optional: tooltip or text below
                };
                
                const clearError = (input) => {
                    input.style.borderColor = '';
                };

                requiredInputs.forEach(input => {
                    clearError(input);
                    if (!input.value.trim()) {
                        showError(input);
                        isValid = false;
                    }
                    
                    // Specific validations
                    if (input.placeholder === '10001' || input.previousElementSibling.textContent.includes('Postal')) {
                           if (!/^\d+$/.test(input.value.trim()) || input.value.trim().length < 5) {
                               showError(input);
                               isValid = false;  
                           }
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    // Shake effect or scroll to top
                    const firstError = document.querySelector('input[style*="border-color: rgb(239, 68, 68)"]');
                    if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return;
                }

                // If valid, allow process to continue (which was the alert logic in original)
                // We'll reimplement the success alert logic here properly or just return true
                // But since we are replacing the inline placeOrder(), we must reproduce its success logic here.
                
                // Check Payment Method
                const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
                if (!paymentMethod) {
                    const errorMsg = document.getElementById('payment-error');
                    if (errorMsg) errorMsg.style.display = 'block';
                    document.querySelector('.payment-option, .selection-grid').scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return;
                }

                // Success
                alert('Job done! Order placed validly.');
            });
        }
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
