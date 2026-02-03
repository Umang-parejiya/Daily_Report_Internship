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
            <div
                style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border); flex-wrap: wrap; gap: 1rem;">
                <div>
                    <h3 style="margin-bottom: 0.25rem;">Order #
                        <?php echo htmlspecialchars($order['order_id']); ?>
                    </h3>
                    <p style="color: var(--text-secondary); font-size: 0.875rem;">
                        Placed on
                        <?php echo date('F j, Y', strtotime($order['date'])); ?>
                    </p>
                </div>
                <div
                    style="text-align: right; display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem;">
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
                        Total: <span style="color: var(--accent);">₹
                            <?php echo number_format($order['total']); ?>
                        </span>
                    </div>

                    <!-- Actions -->
                    <?php if ($status === 'Processing'): ?>
                    <form method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                        <button type="submit" name="cancel_order" class="btn btn-secondary"
                            style="padding: 0.25rem 0.75rem; font-size: 0.8rem; color: var(--error); border-color: var(--error);">
                            Cancel Order
                        </button>
                    </form>
                    <?php elseif ($status !== 'Cancelled'): ?>
                    <button class="btn btn-secondary" style="padding: 0.25rem 0.75rem; font-size: 0.8rem;" disabled>
                        Track Shipment
                    </button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Progress Indicator -->
            <?php if ($status !== 'Cancelled'): ?>
            <div style="margin: 2rem 0; padding: 1.5rem; background: var(--bg-accent); border-radius: 12px;">
                <div class="checkout-progress" style="margin: 0;">
                    <!-- Processing Step -->
                    <div
                        class="step <?php echo ($status === 'Processing' || $status === 'Shipped' || $status === 'Delivered') ? 'completed' : ''; ?>">
                        <div class="step-number">
                            <?php echo ($status === 'Processing' || $status === 'Shipped' || $status === 'Delivered') ? '✓' : '1'; ?>
                        </div>
                        <span>Processing</span>
                    </div>
                    <div class="step-divider"></div>

                    <!-- Shipped Step -->
                    <div
                        class="step <?php echo ($status === 'Shipped' || $status === 'Delivered') ? 'completed' : ($status === 'Processing' ? '' : ''); ?>">
                        <div class="step-number">
                            <?php echo ($status === 'Shipped' || $status === 'Delivered') ? '✓' : '2'; ?>
                        </div>
                        <span>Shipped</span>
                    </div>
                    <div class="step-divider"></div>

                    <!-- Delivered Step -->
                    <div class="step <?php echo ($status === 'Delivered') ? 'completed' : ''; ?>">
                        <div class="step-number">
                            <?php echo ($status === 'Delivered') ? '✓' : '3'; ?>
                        </div>
                        <span>Delivered</span>
                    </div>
                </div>

                <!-- Status Message -->
                <div style="text-align: center; margin-top: 1rem; font-size: 0.9rem; color: var(--text-secondary);">
                    <?php 
                                if ($status === 'Processing') {
                                    echo '📦 Your order is being prepared';
                                } elseif ($status === 'Shipped') {
                                    echo '🚚 Your order is on the way';
                                } elseif ($status === 'Delivered') {
                                    echo '✅ Your order has been delivered';
                                }
                                ?>
                </div>
            </div>
            <?php else: ?>
            <!-- Cancelled Order Message -->
            <div style="margin: 2rem 0; padding: 1.5rem; background: #fee2e2; border-radius: 12px; text-align: center;">
                <div style="font-size: 2rem; margin-bottom: 0.5rem;">❌</div>
                <div style="font-weight: 600; color: #991b1b; margin-bottom: 0.25rem;">Order Cancelled</div>
                <div style="font-size: 0.875rem; color: #7f1d1d;">This order has been cancelled and will not be
                    processed.</div>
            </div>
            <?php endif; ?>

            <div class="table-container" style="border: none; box-shadow: none;">
                <table style="width: 100%;">
                    <thead>
                        <tr style="background: var(--bg-accent); font-size: 0.85rem;">
                            <th style="padding: 0.75rem;">Product</th>
                            <th style="padding: 0.75rem;">Qty</th>
                            <th style="padding: 0.75rem; text-align: right;">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order['items'] as $item): ?>
                        <?php
                                    // Find product details
                                    $product_name = 'Unknown Product';
                                    $product_img = '';
                                    foreach ($products as $p) {
                                        if ($p['id'] === $item['product_id']) {
                                            $product_name = $p['name'];
                                            // Fallback logic for image if needed, though session usually stores text.
                                            // But wait, session stores items as [pid, qty, price]. 
                                            // We need to look up details again or store them.
                                            // Storing just IDs logic above.
                                            $product_img = $p['image']; // Or gallery logic if we wanted dynamic
                                            break;
                                        }
                                    }
                                    ?>
                        <tr>
                            <td style="padding: 0.75rem;">
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <div
                                        style="width: 40px; height: 40px; background: #f4f4f5; border-radius: 4px; overflow: hidden; flex-shrink: 0;">
                                        <?php if($product_img): ?>
                                        <img src="<?php echo htmlspecialchars($product_img); ?>" alt=""
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                        <?php endif; ?>
                                    </div>
                                    <span style="font-weight: 500; font-size: 0.9rem;">
                                        <?php echo htmlspecialchars($product_name); ?>
                                    </span>
                                </div>
                            </td>
                            <td style="padding: 0.75rem; font-size: 0.9rem;">
                                <?php echo $item['quantity']; ?>
                            </td>
                            <td style="padding: 0.75rem; text-align: right; font-weight: 500; font-size: 0.9rem;">
                                ₹
                                <?php echo number_format($item['price']); ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endforeach; ?>
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