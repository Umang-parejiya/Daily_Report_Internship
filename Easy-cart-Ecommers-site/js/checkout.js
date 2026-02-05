/**
 * checkout.js
 * Handles checkout interactions.
 * Shipping option selection, visual highlighting, and total pricing update.
 */

document.addEventListener('DOMContentLoaded', function () {

    // --- Helper Formatters ---
    const parsePrice = (str) => {
        if (!str) return 0;
        if (str.toUpperCase() === 'FREE') return 0;
        return parseInt(str.replace(/[^\d]/g, ''), 10);
    };
    const formatPrice = (num) => '₹' + num.toLocaleString('en-IN');

    // --- Select Elements ---
    const shippingForm = document.getElementById('shippingForm');
    const subtotalElement = document.getElementById('subtotal-value'); // Subtotal value
    const shippingElement = document.getElementById('shipping-value'); // Shipping value
    const totalElement = document.getElementById('total-value'); // Total value
    const completeOrderBtn = document.getElementById('complete-order-btn');

    if (shippingForm && subtotalElement && shippingElement && totalElement) {

        const shippingInputs = shippingForm.querySelectorAll('input[type="radio"]');

        const updateCheckout = () => {
            // 1. Highlight Selection
            shippingInputs.forEach(input => {
                const card = input.closest('.selection-card');
                if (input.checked) {
                    card.classList.add('active');
                    // Styles as per requirements/mockup behavior
                    card.style.borderColor = 'var(--primary)';
                    card.style.background = 'var(--bg-accent)';

                    // 2. [Phase 4/5] AJAX Update Shipping & Totals
                    // Recalculates tax, shipping cost, and final total on server side
                    const selectedMethod = input.value;
                    const formData = new FormData();
                    formData.append('ajax_update_shipping', '1');
                    formData.append('shipping_method', selectedMethod);

                    // Visual feedback
                    if (totalElement) totalElement.style.opacity = '0.5';

                    fetch('checkout', {
                        method: 'POST',
                        body: formData
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                if (shippingElement) {
                                    shippingElement.textContent = data.formatted_shipping;
                                    shippingElement.style.color = (data.shipping_cost == 0) ? 'var(--success)' : '';
                                }

                                const taxElement = document.getElementById('tax-value');
                                if (taxElement) taxElement.textContent = data.formatted_tax;

                                if (totalElement) {
                                    totalElement.textContent = data.formatted_total;
                                    totalElement.style.opacity = '1';
                                }
                            }
                        })
                        .catch(e => {
                            console.error('Shipping update failed', e);
                            if (totalElement) totalElement.style.opacity = '1';
                        });

                } else {
                    card.classList.remove('active');
                    card.style.borderColor = '';
                    card.style.background = '';
                }
            });
        };

        // Initial run
        updateCheckout();

        // Listener
        shippingInputs.forEach(input => {
            input.addEventListener('change', updateCheckout);
        });
    }


    // --- Payment Selection Highlight ---
    const paymentInputs = document.querySelectorAll('input[name="payment_method"]');
    if (paymentInputs.length > 0) {
        const updatePaymentHighlight = () => {
            paymentInputs.forEach(input => {
                const card = input.closest('.selection-card');
                if (input.checked) {
                    card.classList.add('active');
                    card.style.borderColor = 'var(--primary)';
                    card.style.background = 'var(--bg-accent)';
                } else {
                    card.classList.remove('active');
                    card.style.borderColor = '';
                    card.style.background = '';
                }
            });
        };

        paymentInputs.forEach(input => {
            input.addEventListener('change', updatePaymentHighlight);
        });

        // Initial check (usually none selected by default, or maybe one)
        updatePaymentHighlight();
    }

    // --- Complete Order Logic (Added for Task 1, 2, 3) ---
    if (completeOrderBtn) {
        completeOrderBtn.addEventListener('click', function (e) {
            e.preventDefault();

            // 1. Gather Data
            const formData = new FormData();
            formData.append('ajax_place_order', '1');

            // Shipping Method
            const selectedShipping = document.querySelector('input[name="shipping_method"]:checked');
            if (selectedShipping) {
                formData.append('selectedMethod', selectedShipping.value);
            }

            // Payment Method
            const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
            if (selectedPayment) {
                formData.append('payment_method', selectedPayment.value);
            } else {
                alert('Please select a payment method');
                return;
            }

            // Contact/Address Data (Manually selecting inputs since they might not be in a single <form>)
            const fields = ['email', 'firstname', 'lastname', 'street', 'city', 'region', 'postcode', 'telephone'];
            let valid = true;
            fields.forEach(field => {
                const input = document.querySelector(`input[name="${field}"]`);
                if (input) {
                    if (input.hasAttribute('required') && !input.value.trim()) {
                        valid = false;
                        input.style.borderColor = 'red';
                    } else {
                        input.style.borderColor = '';
                    }
                    formData.append(field, input.value.trim());
                }
            });

            if (!valid) {
                alert('Please fill in all required fields.');
                return;
            }

            // 2. Send AJAX Request
            completeOrderBtn.disabled = true;
            completeOrderBtn.textContent = 'Processing...';

            fetch('checkout.php', {
                method: 'POST',
                body: formData
            })
                .then(async res => {
                    const text = await res.text();
                    try {
                        const data = JSON.parse(text);
                        if (data.success) {
                            window.location.href = data.redirect;
                        } else {
                            alert('Order Error: ' + data.message);
                            completeOrderBtn.disabled = false;
                            completeOrderBtn.textContent = 'Complete Order';
                        }
                    } catch (e) {
                        console.error('Server Error:', text);
                        alert('Server did not return valid JSON. Check console for details.');
                        completeOrderBtn.disabled = false;
                        completeOrderBtn.textContent = 'Complete Order';
                    }
                })
                .catch(err => {
                    // console.error(err);
                    // alert('Network Error: ' + (err.message || 'An error occurred'));
                    // completeOrderBtn.disabled = false;
                    completeOrderBtn.textContent = 'Complete Order';
                });
        });
    }
});
