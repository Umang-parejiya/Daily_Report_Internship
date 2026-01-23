/**
 * cart.js
 * Handles UI interactions on the cart page.
 * Updates quantities, prices, and subtotals dynamically.
 * Note: Session persistence happens via form submission or future AJAX.
 */

document.addEventListener('DOMContentLoaded', function () {
    const cartItemsContainer = document.querySelector('.cart-items');
    if (!cartItemsContainer) return;

    // Delegate events for efficiency or attach to buttons directly
    const qtyForms = document.querySelectorAll('.qty-control');
    // Helper to valid number format
    const formatPrice = (num) => '₹' + num.toLocaleString('en-IN');

    qtyForms.forEach(form => {
        const decreaseBtn = form.querySelector('button[value="decrease"]');
        const increaseBtn = form.querySelector('button[value="increase"]');
        const display = form.querySelector('.qty-display');

        decreaseBtn.addEventListener('click', function (e) {
            e.preventDefault();
            let currentQty = parseInt(display.textContent, 10);
            if (currentQty > 1) {
                currentQty--;
                display.textContent = currentQty;
                updateTotals();
            }
        });

        increaseBtn.addEventListener('click', function (e) {
            e.preventDefault();
            let currentQty = parseInt(display.textContent, 10);
            currentQty++;
            display.textContent = currentQty;
            updateTotals();
        });
    });

    // Handle Remove Button
    const removeButtons = document.querySelectorAll('.remove-btn');
    removeButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            if (confirm('Remove this item from cart? (Visual only)')) {
                const card = this.closest('.cart-item-card');
                card.remove();
                updateTotals();

                // Show empty state if no items left
                if (document.querySelectorAll('.cart-item-card').length === 0) {
                    location.reload();
                }
            }
        });
    });

    function updateTotals() {
        let newSubtotal = 0;

        // Recalculate based on current DOM state
        document.querySelectorAll('.cart-item-card').forEach(card => {
            const priceEl = card.querySelector('.cart-item-price');
            const qtyGlobal = card.querySelector('.qty-display').textContent;

            // Per instructions: Read from data-price attribute
            const price = parseInt(priceEl.dataset.price || 0, 10);
            const qty = parseInt(qtyGlobal, 10);

            newSubtotal += (price * qty);
        });

        // Update Subtotal/Total
        const summaryTable = document.querySelector('.table-container table');
        if (summaryTable) {
            const subtotalCell = summaryTable.rows[0].cells[1];
            // Total Est. might be row 2 or 3 depending on markup (Subtotal, Delivery, Total)
            // cart.php: Subtotal (0), Delivery (1), Total (2)
            const totalCell = summaryTable.rows[2].cells[1].querySelector('strong');

            subtotalCell.textContent = formatPrice(newSubtotal);
            if (totalCell) totalCell.textContent = formatPrice(newSubtotal);
        }
    }

});
