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

                    // 2. Update Shipping Text & Total
                    const priceText = card.querySelector('.card-price').textContent.trim();
                    const shippingCost = parsePrice(priceText);
                    const subtotal = parsePrice(subtotalElement.textContent);

                    // Update Shipping Display
                    if (shippingCost === 0) {
                        shippingElement.textContent = 'FREE';
                        shippingElement.style.color = 'var(--success)';
                    } else {
                        shippingElement.textContent = formatPrice(shippingCost);
                        shippingElement.style.color = '';
                    }

                    // Check for discount based on quantity
                    const discountElement = document.getElementById('discount-value');
                    let discount = 0;
                    if (discountElement) {
                        // Use data-value for precision, fallback to text parsing
                        discount = parseFloat(discountElement.getAttribute('data-value')) || parsePrice(discountElement.textContent);
                    }

                    // Calculate Tax (18%) on (Subtotal - Discount)
                    // Shipping is added AFTER tax
                    const taxableAmount = subtotal - discount;
                    const taxAmount = Math.round(taxableAmount * 0.18);

                    const taxElement = document.getElementById('tax-value');
                    if (taxElement) {
                        taxElement.textContent = formatPrice(taxAmount);
                    }

                    // Update Total Display (Subtotal - Discount + Tax + Shipping)
                    const newTotal = taxableAmount + taxAmount + shippingCost;
                    totalElement.textContent = formatPrice(newTotal);

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
