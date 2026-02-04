<?php include 'includes/header.php'; ?>

<div class="container">
    <section class="section">
        <div class="section-header">
            <h1 class="section-title">My Orders</h1>
            <p class="section-subtitle">Track and manage your orders</p>
        </div>

        <!-- Shopping Process Progress Indicator -->
        <div class="checkout-progress" style="margin-bottom: 2rem;">
            <div class="step completed">
                <div class="step-number">✓</div>
                <span>Cart</span>
            </div>
            <div class="step-divider"></div>
            <div class="step completed">
                <div class="step-number">✓</div>
                <span>Information</span>
            </div>
            <div class="step-divider"></div>
            <div class="step completed active">
                <div class="step-number">✓</div>
                <span>Complete</span>
            </div>
        </div>

        <?php if (count($my_orders) > 0): ?>
        <?php foreach ($my_orders as $order): ?>
        <div class="card" style="margin-bottom: 2rem;">
            <!-- Order Header / Summary -->
            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; flex-wrap: wrap;">
                <div>
                    <h3 style="margin-bottom: 0.25rem;">Order #<?php echo htmlspecialchars($order['order_id']); ?></h3>
                    <p style="color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 0.25rem;">
                        Placed on <?php echo date('F j, Y', strtotime($order['date'])); ?>
                    </p>
                    <p style="color: var(--text-secondary); font-size: 0.875rem;">
                        Shipping: <strong><?php echo htmlspecialchars($order['shipping_type']); ?></strong>
                    </p>
                </div>
                
                <div style="text-align: right; display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem;">
                    <!-- Status Badge -->
                    <span style="padding: 0.25rem 0.75rem; border-radius: 99px; font-size: 0.875rem; font-weight: 600; display: inline-block;
                                <?php 
                                $status = $order['status'];
                                if ($status === 'Delivered') {
                                    echo 'background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0;';
                                } elseif ($status === 'Shipped') {
                                    echo 'background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe;';
                                } elseif ($status === 'Cancelled') {
                                    echo 'background: #fee2e2; color: #991b1b; border: 1px solid #fecaca;';
                                } else {
                                    echo 'background: #fef3c7; color: #92400e; border: 1px solid #fde68a;';
                                }
                                ?>">
                        <?php echo htmlspecialchars($status); ?>
                    </span>

                    <!-- Total -->
                    <div style="font-size: 1.1rem; font-weight: 700; color: var(--text-primary);">
                        Total: <span style="color: var(--accent);">₹<?php echo number_format($order['total']); ?></span>
                    </div>

                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                        <button type="button" class="btn btn-primary" 
                                onclick="toggleOrderDetails('details-<?php echo $order['order_id']; ?>')"
                                style="padding: 0.25rem 0.75rem; font-size: 0.8rem; background: var(--accent); color: white;">
                            View Details ▼
                        </button>
                        
                        <?php if ($status === 'Processing'): ?>
                        <form method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                            <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                            <button type="submit" name="cancel_order" class="btn btn-secondary"
                                style="padding: 0.25rem 0.75rem; font-size: 0.8rem; color: var(--error); border-color: var(--error);">
                                Cancel
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Hidden Details Section (Dropdown) -->
            <div id="details-<?php echo $order['order_id']; ?>" style="display: none; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border);">
                
                <!-- Items Table -->
                <div class="table-container" style="border: none; box-shadow: none; margin-bottom: 2rem;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="text-align: left; font-size: 0.85rem; color: var(--text-secondary); border-bottom: 2px solid var(--bg-accent);">
                                <th style="padding: 0.75rem;">Product</th>
                                <th style="padding: 0.75rem;">Price</th>
                                <th style="padding: 0.75rem; text-align: center;">Qty</th>
                                <th style="padding: 0.75rem; text-align: right;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order['items'] as $item): ?>
                            <tr style="border-bottom: 1px solid var(--bg-accent);">
                                <td style="padding: 1rem 0.5rem;">
                                    <div style="font-weight: 500; font-size: 0.9rem;"><?php echo htmlspecialchars($item['name']); ?></div>
                                </td>
                                <td style="padding: 1rem 0.5rem; font-size: 0.9rem;">₹<?php echo number_format($item['price']); ?></td>
                                <td style="padding: 1rem 0.5rem; text-align: center; font-size: 0.9rem;"><?php echo $item['quantity']; ?></td>
                                <td style="padding: 1rem 0.5rem; text-align: right; font-weight: 600; font-size: 0.9rem;">₹<?php echo number_format($item['price'] * $item['quantity']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Order Summary & Info -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: start;">
                    <!-- Info -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div style="background: var(--bg-accent); padding: 0.75rem; border-radius: 8px;">
                            <div style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase;">Payment</div>
                            <div style="font-weight: 600; font-size: 0.9rem; margin-top: 0.25rem;">
                                <?php echo htmlspecialchars($order['payment_method'] ?? 'N/A'); ?>
                            </div>
                        </div>
                        <div style="background: var(--bg-accent); padding: 0.75rem; border-radius: 8px;">
                            <div style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase;">Shipping</div>
                            <div style="font-weight: 600; font-size: 0.9rem; margin-top: 0.25rem;">
                                <?php echo htmlspecialchars($order['shipping_type']); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Price Breakdown -->
                    <div style="background: var(--bg-accent); padding: 1.25rem; border-radius: 12px; font-size: 0.9rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span style="color: var(--text-secondary);">Subtotal</span>
                            <span>₹<?php echo number_format($order['subtotal']); ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span style="color: var(--text-secondary);">Shipping</span>
                            <span>₹<?php echo number_format($order['shipping_amount']); ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span style="color: var(--text-secondary);">Tax</span>
                            <span>₹<?php echo number_format($order['tax_amount']); ?></span>
                        </div>
                        <div style="border-top: 1px dashed var(--border); margin: 0.75rem 0;"></div>
                        <div style="display: flex; justify-content: space-between; font-weight: 700; font-size: 1.1rem; color: var(--accent);">
                            <span>Total</span>
                            <span>₹<?php echo number_format($order['total']); ?></span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <?php endforeach; ?>
        <script>
        function toggleOrderDetails(id) {
            const el = document.getElementById(id);
            if (el.style.display === 'none') {
                el.style.display = 'block';
            } else {
                el.style.display = 'none';
            }
        }
        </script>
        <?php else: ?>
        <div class="card"
            style="text-align: center; padding: 4rem 2rem; border: 2px dashed var(--border); background: transparent;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">📦</div>
            <h3 style="margin-bottom: 0.5rem; font-size: 1.5rem;">No orders yet</h3>
            <p style="color: var(--text-secondary); margin-bottom: 2rem;">When you place an order, it will appear here.
            </p>
            <a href="plp.php" class="btn btn-primary" style="padding: 0.75rem 2rem;">
                Start Shopping
            </a>
        </div>
        <?php endif; ?>
    </section>
</div>

<?php include 'includes/footer.php'; ?>