<?php include 'includes/header.php'; ?>

<div class="container">
    <section class="section">
        <!-- Modern Checkout Layout -->

        <!-- 1. Progress Indicator -->
        <div class="checkout-progress">
            <div class="step completed">
                <div class="step-number">✓</div>
                <span>Cart</span>
            </div>
            <div class="step-divider"></div>
            <div class="step active">
                <div class="step-number">2</div>
                <span>Information</span>
            </div>
            <div class="step-divider"></div>
            <div class="step">
                <div class="step-number">3</div>
                <span>Complete</span>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 3rem; align-items: start;">

            <!-- Left Column: Forms -->
            <div class="checkout-form">
                <h2>Shipping Information</h2>

                <!-- Contact & Address Form -->
                <div class="card mb-4" style="border: none; padding: 0; background: transparent; box-shadow: none;">
                    <form class="form-fieldset" style="margin-top: 0;">
                        <h3>Contact Details</h3>
                        <div class="checkout-form-grid">
                            <div class="form-group full-width">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-input" placeholder="john@example.com" required value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">First Name</label>
                                <input type="text" name="firstname" class="form-input" placeholder="John" required value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="lastname" class="form-input" placeholder="Doe" required value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>">
                            </div>
                            <div class="form-group full-width">
                                <label class="form-label">Address</label>
                                <input type="text" name="street" class="form-input" placeholder="123 Main St" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-input" placeholder="New York" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Region/State</label>
                                <input type="text" name="region" class="form-input" placeholder="NY" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Postal Code</label>
                                <input type="text" name="postcode" class="form-input" placeholder="10001" required>
                            </div>
                            <div class="form-group full-width">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" name="telephone" class="form-input" placeholder="+1 (555) 000-0000" required>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Shipping Method Selection (New Card Choice) -->
                <div class="mb-4">
                    <h3>Shipping Method</h3>
                    <form method="POST" action="" id="shippingForm">
                        <div class="selection-grid">
                            <?php foreach ($shipping_options as $key => $option): ?>
                            <?php 
                                // [Phase 5] UI Logic: Disable invalid options
                                // Check if this option is valid for the current Cart Type
                                $is_valid = in_array($key, $valid_methods);
                                $disabled = !$is_valid;
                                $opacity = $disabled ? '0.5' : '1';
                                $cursor = $disabled ? 'not-allowed' : 'pointer';
                            ?>
                            <label class="selection-card"
                                style="opacity: <?php echo $opacity; ?>; cursor: <?php echo $cursor; ?>;">
                                <input type="radio" name="shipping_method" value="<?php echo $option['id']; ?>" <?php
                                    echo ($selected_shipping===$key) ? 'checked' : '' ; ?>
                                <?php echo $disabled ? 'disabled' : ''; // Prevent selection ?> >
                                <div class="card-content">
                                    <div class="card-icon">
                                        <?php echo $option['icon']; ?>
                                    </div>
                                    <div class="card-title">
                                        <?php echo $option['name']; ?>
                                        <?php if(!$is_valid): ?>
                                        <!-- Inform user why it's disabled -->
                                        <span style="font-size:0.7em; color:var(--error);">(
                                            <?php echo ucfirst($cart_type); ?> Only)
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-desc">
                                        <?php echo $option['delivery']; ?>
                                    </div>
                                    <div class="card-price">
                                        <?php echo ($option['cost'] > 0) ? '₹' . number_format($option['cost']) : 'FREE'; ?>
                                    </div>
                                </div>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </form>
                </div>

                <!-- Payment Method Selection (New Card Choice) -->
                <div class="mb-4">
                    <h3>Payment Method</h3>
                    <div class="selection-grid" style="grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));">

                        <label class="selection-card">
                            <input type="radio" name="payment_method" value="cod">
                            <div class="card-content" style="align-items: center; text-align: center; padding: 1rem;">
                                <div class="card-icon">💵</div>
                                <div class="card-title" style="font-size: 0.9rem;">COD</div>
                                <div class="card-desc" style="font-size: 0.75rem;">Pay on delivery</div>
                            </div>
                        </label>

                        <label class="selection-card">
                            <input type="radio" name="payment_method" value="card">
                            <div class="card-content" style="align-items: center; text-align: center; padding: 1rem;">
                                <div class="card-icon">💳</div>
                                <div class="card-title" style="font-size: 0.9rem;">Card</div>
                                <div class="card-desc" style="font-size: 0.75rem;">Credit/Debit</div>
                            </div>
                        </label>

                        <label class="selection-card">
                            <input type="radio" name="payment_method" value="upi">
                            <div class="card-content" style="align-items: center; text-align: center; padding: 1rem;">
                                <div class="card-icon">📱</div>
                                <div class="card-title" style="font-size: 0.9rem;">UPI</div>
                                <div class="card-desc" style="font-size: 0.75rem;">GPay/PhonePe</div>
                            </div>
                        </label>

                        <label class="selection-card">
                            <input type="radio" name="payment_method" value="netbanking">
                            <div class="card-content" style="align-items: center; text-align: center; padding: 1rem;">
                                <div class="card-icon">🏦</div>
                                <div class="card-title" style="font-size: 0.9rem;">NetBanking</div>
                                <div class="card-desc" style="font-size: 0.75rem;">All Banks</div>
                            </div>
                        </label>

                    </div>
                    <p id="payment-error"
                        style="color: var(--error); font-size: 0.875rem; margin-top: 1rem; display: none;">
                        ⚠️ Please select a payment method to continue
                    </p>
                </div>

                <button id="complete-order-btn" class="btn btn-primary" style="width: 100%; padding: 1rem; font-size: 1rem;">
                    Complete Order
                </button>
            </div>

            <!-- Right Column: Order Summary (Sticky) -->
            <div class="checkout-sidebar">
                <div class="card">
                    <h3 class="mb-3">Order Summary</h3>

                    <!-- Items List -->
                    <div style="max-height: 300px; overflow-y: auto; margin-bottom: 1.5rem; padding-right: 0.5rem;">
                        <?php foreach ($cart_items as $item): ?>
                        <div
                            style="display: flex; gap: 1rem; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border);">
                            <div
                                style="width: 60px; height: 60px; background: var(--bg-accent); border-radius: var(--radius-sm); overflow: hidden;">
                                <img src="<?php echo htmlspecialchars($item['product']['image']); ?>" alt=""
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div style="flex: 1;">
                                <div
                                    style="font-weight: 600; font-size: 0.9rem; line-height: 1.3; margin-bottom: 0.25rem;">
                                    <?php echo htmlspecialchars($item['product']['name']); ?>
                                </div>
                                <div style="font-size: 0.85rem; color: var(--text-secondary);">
                                    Qty:
                                    <?php echo $item['quantity']; ?>
                                </div>
                            </div>
                            <div style="font-weight: 600;">
                                ₹
                                <?php echo number_format($item['subtotal']); ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Totals -->
                    <div style="border-top: 2px solid var(--border); padding-top: 1rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span style="color: var(--text-secondary);">Subtotal</span>
                            <span id="subtotal-value">₹
                                <?php echo number_format($subtotal); ?>
                            </span>
                        </div>
                        <!-- Calculate total quantity for discount quantity -->
                        <?php if ($discount > 0): ?>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span style="color: var(--success);">Discount (
                                <?php echo $discount_percentage; ?>% off based on even quantity)
                            </span>
                            <span id="discount-value" style="color: var(--success);"
                                data-value="<?php echo $discount; ?>">
                                -₹
                                <?php echo number_format($discount); ?>
                            </span>
                        </div>
                        <?php endif; ?>

                        <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                            <span style="color: var(--text-secondary);">Shipping</span>
                            <span id="shipping-value"
                                style="<?php echo ($shipping == 0) ? 'color: var(--success);' : ''; ?>">
                                <?php echo ($shipping == 0) ? 'FREE' : '₹' . number_format($shipping); ?>
                            </span>
                        </div>

                        <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                            <span style="color: var(--text-secondary);">GST (18%)</span>
                            <span id="tax-value">₹
                                <?php echo number_format($tax); ?>
                            </span>
                        </div>

                        <div
                            style="display: flex; justify-content: space-between; margin-top: 1rem; padding-top: 1rem; border-top: 1px dashed var(--border);">
                            <span style="font-size: 1.25rem; font-weight: 700;">Total</span>
                            <span id="total-value" style="font-size: 1.25rem; font-weight: 700; color: var(--accent);">
                                ₹
                                <?php echo number_format($total); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>