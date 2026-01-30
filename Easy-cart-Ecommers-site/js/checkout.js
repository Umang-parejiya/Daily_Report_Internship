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

                    fetch('checkout.php', {
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
});
