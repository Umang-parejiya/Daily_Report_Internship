<?php include 'includes/header.php'; ?>

<div class="container">
    <section class="section">

        <?php if (count($cart_items) > 0): ?>

        <div class="section-header">
            <h1 class="section-title">Your Shopping Cart</h1>
            <p class="section-subtitle">
                You have
                <?php echo count($_SESSION['cart']); ?> item(s) in your cart
            </p>
        </div>

        <!-- Progress Indicator -->
        <div class="checkout-progress">
            <div class="step active">
                <div class="step-number">1</div>
                <span>Cart</span>
            </div>
            <div class="step-divider"></div>
            <div class="step">
                <div class="step-number">2</div>
                <span>Information</span>
            </div>
            <div class="step-divider"></div>
            <div class="step">
                <div class="step-number">3</div>
                <span>Complete</span>
            </div>
        </div>

        <div class="cart-layout">
            <!-- Left Column: Product List -->
            <div class="cart-items">

                <?php foreach ($cart_items as $item): ?>
                <div class="cart-item-card">
                    <!-- Product Image -->
                    <img src="<?php echo htmlspecialchars($item['image']); ?>"
                        alt="<?php echo htmlspecialchars($item['name']); ?>" class="cart-item-image">

                    <!-- Details -->
                    <div class="cart-item-details">
                        <div>
                            <div class="cart-item-header">
                                <h3 class="cart-item-title">
                                    <a href="pdp.php?id=<?php echo $item['id']; ?>"
                                        style="color: inherit; text-decoration: none;">
                                        <?php echo htmlspecialchars($item['name']); ?>
                                    </a>
                                </h3>
                                <div class="cart-item-price" data-price="<?php echo $item['price']; ?>">
                                    ₹
                                    <?php echo number_format($item['price']); ?>
                                </div>
                            </div>
                            <div class="cart-item-brand">
                                Brand:
                                <?php echo htmlspecialchars($item['brand']); ?>
                            </div>
                        </div>

                        <div class="cart-item-actions">
                            <!-- Interactive Qty Control -->
                            <form method="POST" class="qty-control">
                                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">

                                <button type="submit" name="action" value="decrease" class="qty-btn" title="Decrease">
                                    −
                                </button>

                                <div class="qty-display">
                                    <?php echo $item['qty']; ?>
                                </div>

                                <button type="submit" name="action" value="increase" class="qty-btn" title="Increase">
                                    +
                                </button>
                            </form>

                            <!-- Remove Button -->
                            <a href="cart.php?remove=<?php echo $item['id']; ?>" class="remove-btn">
                                🗑 Remove
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

                <div style="margin-top: 1rem;">
                    <a href="plp.php" class="btn btn-secondary">
                        ← Continue Shopping
                    </a>
                </div>
            </div>

            <!-- Right Column: Summary Box -->
            <div class="cart-summary checkout-sidebar">
                <div class="card">
                    <h3 class="mb-3">Order Summary</h3>

                    <div class="table-container" style="border: none; background: transparent;">
                        <table style="margin-bottom: 1rem;">
                            <tbody>
                                <tr>
                                    <td style="padding: 0.5rem 0; border: none; color: var(--text-secondary);">Subtotal
                                    </td>
                                    <td style="padding: 0.5rem 0; border: none; text-align: right; font-weight: 600;">
                                        ₹
                                        <?php echo number_format($subtotal); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 0.5rem 0; border: none; color: var(--text-secondary);">Delivery
                                        Type</td>
                                    <td style="padding: 0.5rem 0; border: none; text-align: right; font-style: italic; color: <?php echo ($cart_type === 'express') ? '#10b981' : '#f59e0b'; ?>;"
                                        id="delivery-type-label">
                                        Your delivery is
                                        <?php echo ucfirst($cart_type); ?> Delivery
                                    </td>
                                </tr>
                                <!-- Calculate total quantity discount even logic -->
                                <?php if ($discount > 0): ?>
                                <tr>
                                    <td style="padding: 0.5rem 0; border: none; color: var(--success);">
                                        Discount (
                                        <?php echo $discount_percentage; ?>% off based on even quantity)
                                    </td>
                                    <td
                                        style="padding: 0.5rem 0; border: none; text-align: right; font-weight: 600; color: var(--success);">
                                        -₹
                                        <?php echo number_format($discount); ?>
                                    </td>
                                </tr>
                                <?php endif; ?>

                                <tr style="border-top: 2px solid var(--border);">
                                    <td style="padding: 1rem 0; border: none;"><strong style="font-size: 1.1rem;">Total
                                            Est.</strong></td>
                                    <td style="padding: 1rem 0; border: none; text-align: right;"><strong
                                            style="font-size: 1.1rem; color: var(--accent);">₹
                                            <?php echo number_format($total); ?>
                                        </strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <a href="checkout.php" class="btn btn-primary"
                        style="width: 100%; justify-content: center; padding: 1rem;">
                        Proceed to Checkout →
                    </a>

                    <p style="text-align: center; font-size: 0.85rem; color: var(--text-muted); margin-top: 1rem;">
                        🔒 Secure Checkout
                    </p>
                </div>
            </div>
        </div>

        <?php else: ?>

        <!-- EMTPY STATE -->
        <div class="empty-cart-container">
            <span class="empty-icon">🛒</span>
            <h2 style="margin-bottom: 1rem;">Your cart is empty</h2>
            <p style="margin-bottom: 2rem;">Looks like you haven't added anything to your cart yet.</p>
            <a href="plp.php" class="btn btn-primary">Start Shopping</a>
        </div>

        <?php endif; ?>

    </section>
</div>

<?php include 'includes/footer.php'; ?>