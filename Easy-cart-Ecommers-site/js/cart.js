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
        const product_id = form.querySelector('input[name="product_id"]').value;
        const display = form.querySelector('.qty-display');

        form.querySelectorAll('button').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const action = this.value; // increase or decrease

                // Optimistic UI update or wait for server?
                // Server is safer to ensure sync

                const formData = new FormData();
                formData.append('action', action);
                formData.append('product_id', product_id);
                formData.append('ajax_update', '1');

                fetch('cart.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            if (data.removed) {
                                form.closest('.cart-item-card').remove();
                                // If empty, reload to show empty state
                                if (document.querySelectorAll('.cart-item-card').length === 0) {
                                    location.reload();
                                }
                            } else {
                                display.textContent = data.newQty;
                            }

                            // Update globals
                            const summaryTable = document.querySelector('.table-container table');
                            if (summaryTable) {
                                // Subtotal (row 0)  Calculate total quantity discount even logic
                                summaryTable.rows[0].cells[1].textContent = `₹${data.newSubtotal}`;

                                // Handle discount row dynamically
                                let discountRow = summaryTable.querySelector('tr[data-discount-row]');

                                if (data.hasDiscount) {
                                    if (!discountRow) {
                                        // Create discount row if it doesn't exist
                                        discountRow = summaryTable.insertRow(2); // Insert before Total row
                                        discountRow.setAttribute('data-discount-row', 'true');
                                        discountRow.innerHTML = `
                                            <td style="padding: 0.5rem 0; border: none; color: var(--success);">
                                                Discount (<span class="discount-percentage"></span>% off based on even quantity)
                                            </td>
                                            <td style="padding: 0.5rem 0; border: none; text-align: right; font-weight: 600; color: var(--success);">
                                                -₹<span class="discount-amount"></span>
                                            </td>
                                        `;
                                    }
                                    // Update discount values
                                    discountRow.querySelector('.discount-percentage').textContent = data.discountPercentage;
                                    discountRow.querySelector('.discount-amount').textContent = data.newDiscount;
                                } else {
                                    // Remove discount row if it exists and discount is 0
                                    if (discountRow) {
                                        discountRow.remove();
                                    }
                                }

                                // Total (last row)
                                const totalRow = summaryTable.rows[summaryTable.rows.length - 1];
                                totalRow.cells[1].innerHTML = `<strong style="font-size: 1.1rem; color: var(--accent);">₹${data.newTotal}</strong>`;
                            }
                        }
                    })
                    .catch(err => console.error('Cart update failed:', err));
            });
        });
    });

    // Handle Remove Button
    // Handle Remove Button
    const removeButtons = document.querySelectorAll('.remove-btn');
    removeButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            if (!confirm('Are you sure you want to remove this item?')) {
                e.preventDefault();
            }
            // Allow default navigation to cart.php?remove=ID
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
