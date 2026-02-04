<?php include 'includes/header.php'; ?>

<div class="container">
    <section class="section">
        <div class="section-header" style="display: flex; justify-content: space-between; align-items: flex-end;">
            <div>
                <a href="orders.php" style="color: var(--accent); text-decoration: none; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                    ← Back to My Orders
                </a>
                <h1 class="section-title">Order #<?php echo htmlspecialchars($order['increment_id']); ?></h1>
                <p class="section-subtitle">Placed on <?php echo date('F j, Y, g:i a', strtotime($order['created_at'])); ?></p>
            </div>
            <div style="text-align: right;">
                <span style="padding: 0.25rem 0.75rem; border-radius: 99px; font-size: 0.875rem; font-weight: 600;
                            <?php 
                            $status = ucfirst($order['status']);
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
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-top: 2rem;">
            <!-- Main Content: Items -->
            <div class="card" style="padding: 1.5rem;">
                <h3 style="margin-bottom: 1.5rem; border-bottom: 1px solid var(--border); padding-bottom: 0.75rem;">Items in this Order</h3>
                <div class="table-container" style="border: none; box-shadow: none;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="text-align: left; font-size: 0.85rem; color: var(--text-secondary); border-bottom: 2px solid var(--bg-accent);">
                                <th style="padding: 1rem 0.5rem;">Product</th>
                                <th style="padding: 1rem 0.5rem;">Price</th>
                                <th style="padding: 1rem 0.5rem; text-align: center;">Qty</th>
                                <th style="padding: 1rem 0.5rem; text-align: right;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order_items as $item): ?>
                            <tr style="border-bottom: 1px solid var(--bg-accent);">
                                <td style="padding: 1.25rem 0.5rem;">
                                    <div style="display: flex; align-items: center; gap: 1rem;">
                                        <div style="width: 60px; height: 60px; background: var(--bg-accent); border-radius: 8px; overflow: hidden; flex-shrink: 0;">
                                            <?php if($item['image']): ?>
                                                <img src="<?php echo htmlspecialchars($item['image']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <div style="font-weight: 600; font-size: 0.95rem;"><?php echo htmlspecialchars($item['name']); ?></div>
                                            <div style="font-size: 0.75rem; color: var(--text-secondary);">SKU: <?php echo htmlspecialchars($item['sku']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 1.25rem 0.5rem; font-size: 0.9rem;">₹<?php echo number_format($item['price'], 2); ?></td>
                                <td style="padding: 1.25rem 0.5rem; text-align: center; font-size: 0.9rem;"><?php echo $item['quantity']; ?></td>
                                <td style="padding: 1.25rem 0.5rem; text-align: right; font-weight: 600; font-size: 0.9rem;">₹<?php echo number_format($item['row_total'], 2); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Info Cards -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 2rem;">
                    <div style="background: var(--bg-accent); padding: 1rem; border-radius: 12px;">
                        <h4 style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-secondary); margin-bottom: 0.5rem;">Shipping Method</h4>
                        <div style="font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                            <span>📦</span> <?php echo htmlspecialchars($order['shipping_method']); ?>
                        </div>
                    </div>
                    <div style="background: var(--bg-accent); padding: 1rem; border-radius: 12px;">
                        <h4 style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-secondary); margin-bottom: 0.5rem;">Payment Method</h4>
                        <div style="font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                            <span>💳</span> <?php echo htmlspecialchars($order['payment_method'] ?? 'N/A'); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar: Order Summary -->
            <div>
                <div class="card" style="padding: 1.5rem; position: sticky; top: 2rem;">
                    <h3 style="margin-bottom: 1.25rem; font-size: 1.1rem;">Order Summary</h3>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.95rem;">
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--text-secondary);">Subtotal</span>
                            <span>₹<?php echo number_format($order['subtotal'], 2); ?></span>
                        </div>
                        
                        <?php 
                        $discount = ($order['subtotal'] + $order['shipping_amount']) * 1.18 - $order['grand_total']; // Wait, simple reverse math
                        // Actually grand_total = (subtotal - discount + shipping) * 1.18
                        // So (subtotal - discount + shipping) = grand_total / 1.18
                        // discount = subtotal + shipping - (grand_total / 1.18)
                        $discount = ($order['subtotal'] + $order['shipping_amount']) - ($order['grand_total'] / 1.18);
                        if ($discount > 1): // Tolerance for float rounding
                        ?>
                        <div style="display: flex; justify-content: space-between; color: var(--success);">
                            <span>Discount</span>
                            <span>-₹<?php echo number_format($discount, 2); ?></span>
                        </div>
                        <?php endif; ?>

                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--text-secondary);">Shipping</span>
                            <span>₹<?php echo number_format($order['shipping_amount'], 2); ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--text-secondary);">Tax (18%)</span>
                            <span>₹<?php echo number_format($order['tax_amount'], 2); ?></span>
                        </div>
                        <hr style="border: none; border-top: 1px solid var(--border); margin: 0.5rem 0;">
                        <div style="display: flex; justify-content: space-between; font-weight: 700; font-size: 1.1rem; color: var(--accent);">
                            <span>Grand Total</span>
                            <span>₹<?php echo number_format($order['grand_total'], 2); ?></span>
                        </div>
                    </div>

                    <div style="margin-top: 2rem; padding: 1rem; border: 1px solid #bfdbfe; background: #eff6ff; border-radius: 8px; font-size: 0.85rem; color: #1e40af;">
                        Need help with this order? <a href="#" style="color: var(--accent); font-weight: 600;">Contact Support</a> or read our <a href="#" style="color: var(--accent); font-weight: 600;">Return Policy</a>.
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>
