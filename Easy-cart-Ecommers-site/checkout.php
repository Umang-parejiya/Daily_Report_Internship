<?php
// Checkout Page - checkout.php
session_start();

$current_page = 'cart';
$page_title = 'Easy-Cart - Checkout';

// Load products data
require_once 'data/products.php';

// Check if cart is empty
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
    header('Location: cart.php');
    exit;
}

// Get cart items with quantities
$cart_items = [];
$cart_product_ids = array_count_values($_SESSION['cart']);

foreach ($cart_product_ids as $product_id => $quantity) {
    foreach ($products as $product) {
        if ($product['id'] === $product_id) {
            $cart_items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $product['price'] * $quantity
            ];
            break;
        }
    }
}

// Calculate subtotal
$subtotal = 0;
foreach ($cart_items as $item) {
    $subtotal += $item['subtotal'];
}

// Shipping options with delivery estimates
$shipping_options = [
    'free' => [
        'name' => 'Free Shipping', 
        'cost' => 0,
        'delivery' => '6-7 business days'
    ],
    'standard' => [
        'name' => 'Standard Shipping', 
        'cost' => 1000,
        'delivery' => '3-4 business days'
    ],
    'express' => [
        'name' => 'Express Shipping', 
        'cost' => 2000,
        'delivery' => 'Next day delivery'
    ]
];

// Get selected shipping method (default to standard)
$selected_shipping = isset($_POST['shipping_method']) ? $_POST['shipping_method'] : 'standard';

// Validate shipping method
if (!isset($shipping_options[$selected_shipping])) {
    $selected_shipping = 'standard';
}

// Calculate shipping cost
$shipping = $shipping_options[$selected_shipping]['cost'];

// Calculate total
$total = $subtotal + $shipping;

// Include header
include 'includes/header.php';
?>

<div class="container">
    <section class="section">
        <div class="section-header">
            <h1 class="section-title">Checkout</h1>
            <p class="section-subtitle">Complete your order</p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: start;">
            <!-- Shipping Information Form -->
            <div class="card">
                <h3 style="margin-bottom: 1.5rem;">Shipping Information</h3>
                <form class="form-fieldset">
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-input" placeholder="John Doe" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-input" placeholder="john@example.com" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" class="form-input" placeholder="+91 1234567890" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-input" placeholder="Street Address" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">City</label>
                        <input type="text" class="form-input" placeholder="City" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Postal Code</label>
                        <input type="text" class="form-input" placeholder="123456" required>
                    </div>
                </form>
            </div>

            <!-- Order Summary -->
            <div>
                <div class="card" style="margin-bottom: 1.5rem;">
                    <h3 style="margin-bottom: 1rem;">Order Summary</h3>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart_items as $item): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['product']['name']); ?></td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td>₹<?php echo number_format($item['subtotal']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Shipping Method Selection -->
                <div class="card" style="margin-bottom: 1.5rem;">
                    <h3 style="margin-bottom: 1rem;">Shipping Method</h3>
                    <form method="POST" action="" id="shippingForm">
                        <div class="form-group">
                            <label class="form-label">Select Shipping Option</label>
                            <select name="shipping_method" class="form-input" 
                                    onchange="document.getElementById('shippingForm').submit();" 
                                    style="cursor: pointer;">
                                <?php foreach ($shipping_options as $key => $option): ?>
                                    <option value="<?php echo $key; ?>" 
                                            <?php echo ($selected_shipping === $key) ? 'selected' : ''; ?>>
                                        <?php echo $option['name']; ?> 
                                        <?php if ($option['cost'] > 0): ?>
                                            - ₹<?php echo number_format($option['cost']); ?>
                                        <?php else: ?>
                                            - FREE
                                        <?php endif; ?>
                                        (<?php echo $option['delivery']; ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </form>
                    
                    <!-- Display selected shipping details -->
                    <div style="background: var(--bg-accent); padding: 1rem; border-radius: var(--radius-sm); margin-top: 1rem;">
                        <p style="margin: 0; font-size: 0.875rem;">
                            <strong style="color: var(--text-primary);">
                                <?php echo $shipping_options[$selected_shipping]['name']; ?>
                            </strong>
                            <br>
                            <span style="color: var(--text-secondary);">
                                📦 Estimated Delivery: <strong><?php echo $shipping_options[$selected_shipping]['delivery']; ?></strong>
                            </span>
                        </p>
                    </div>
                    
                    <p style="color: var(--text-secondary); font-size: 0.875rem; margin-top: 0.75rem;">
                        <em>💡 Total will update automatically when you change shipping method</em>
                    </p>
                </div>

                <!-- Payment Method Selection -->
                <div class="card" style="margin-bottom: 1.5rem;">
                    <h3 style="margin-bottom: 1rem;">Payment Method <span style="color: var(--error);">*</span></h3>
                    
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <!-- Cash on Delivery -->
                        <label class="payment-option" style="display: flex; align-items: center; padding: 1rem; border: 2px solid var(--border); border-radius: var(--radius-sm); cursor: pointer; transition: all 0.3s ease;">
                            <input type="radio" name="payment_method" value="cod" id="payment_cod" 
                                   style="margin-right: 1rem; width: 20px; height: 20px; cursor: pointer;">
                            <div style="flex: 1;">
                                <strong style="display: block; color: var(--text-primary); margin-bottom: 0.25rem;">
                                    💵 Cash on Delivery (COD)
                                </strong>
                                <span style="font-size: 0.875rem; color: var(--text-secondary);">
                                    Pay when you receive your order
                                </span>
                            </div>
                        </label>

                        <!-- Credit/Debit Card -->
                        <label class="payment-option" style="display: flex; align-items: center; padding: 1rem; border: 2px solid var(--border); border-radius: var(--radius-sm); cursor: pointer; transition: all 0.3s ease;">
                            <input type="radio" name="payment_method" value="card" id="payment_card" 
                                   style="margin-right: 1rem; width: 20px; height: 20px; cursor: pointer;">
                            <div style="flex: 1;">
                                <strong style="display: block; color: var(--text-primary); margin-bottom: 0.25rem;">
                                    💳 Credit / Debit Card
                                </strong>
                                <span style="font-size: 0.875rem; color: var(--text-secondary);">
                                    Visa, MasterCard, Rupay accepted
                                </span>
                            </div>
                        </label>

                        <!-- UPI -->
                        <label class="payment-option" style="display: flex; align-items: center; padding: 1rem; border: 2px solid var(--border); border-radius: var(--radius-sm); cursor: pointer; transition: all 0.3s ease;">
                            <input type="radio" name="payment_method" value="upi" id="payment_upi" 
                                   style="margin-right: 1rem; width: 20px; height: 20px; cursor: pointer;">
                            <div style="flex: 1;">
                                <strong style="display: block; color: var(--text-primary); margin-bottom: 0.25rem;">
                                    📱 UPI
                                </strong>
                                <span style="font-size: 0.875rem; color: var(--text-secondary);">
                                    Google Pay, PhonePe, Paytm, etc.
                                </span>
                            </div>
                        </label>

                        <!-- Net Banking -->
                        <label class="payment-option" style="display: flex; align-items: center; padding: 1rem; border: 2px solid var(--border); border-radius: var(--radius-sm); cursor: pointer; transition: all 0.3s ease;">
                            <input type="radio" name="payment_method" value="netbanking" id="payment_netbanking" 
                                   style="margin-right: 1rem; width: 20px; height: 20px; cursor: pointer;">
                            <div style="flex: 1;">
                                <strong style="display: block; color: var(--text-primary); margin-bottom: 0.25rem;">
                                    🏦 Net Banking
                                </strong>
                                <span style="font-size: 0.875rem; color: var(--text-secondary);">
                                    All major banks supported
                                </span>
                            </div>
                        </label>
                    </div>

                    <p id="payment-error" style="color: var(--error); font-size: 0.875rem; margin-top: 1rem; display: none;">
                        ⚠️ Please select a payment method to continue
                    </p>
                </div>

                <style>
                    .payment-option:has(input:checked) {
                        border-color: var(--accent) !important;
                        background: var(--accent-light);
                    }
                    .payment-option:hover {
                        border-color: var(--accent);
                        background: var(--bg-accent);
                    }
                </style>

                <script>
                    // Add change event to hide error when payment method is selected
                    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
                        radio.addEventListener('change', function() {
                            document.getElementById('payment-error').style.display = 'none';
                        });
                    });
                </script>

                <!-- Payment Summary -->
                <div class="card price-summary">
                    <h3>Payment Summary</h3>
                    <div class="table-container">
                        <table>
                            <tbody>
                                <tr>
                                    <td>Subtotal</td>
                                    <td>₹<?php echo number_format($subtotal); ?></td>
                                </tr>
                                <tr>
                                    <td>Shipping (<?php echo $shipping_options[$selected_shipping]['name']; ?>)</td>
                                    <td>
                                        <?php if ($shipping > 0): ?>
                                            ₹<?php echo number_format($shipping); ?>
                                        <?php else: ?>
                                            <span style="color: var(--success); font-weight: 600;">FREE</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr style="border-top: 2px solid var(--border);">
                                    <td><strong style="font-size: 1.125rem;">Total</strong></td>
                                    <td><strong style="font-size: 1.125rem; color: var(--accent);">₹<?php echo number_format($total); ?></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <button class="btn btn-primary" style="width: 100%; margin-top: 1rem;" 
                            onclick="placeOrder(); return false;">
                        Place Order - ₹<?php echo number_format($total); ?>
                    </button>
                    <a href="cart.php" class="btn btn-secondary" style="width: 100%; margin-top: 0.5rem;">
                        Back to Cart
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    function placeOrder() {
        // Check if payment method is selected
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
        
        if (!paymentMethod) {
            // Show error message
            document.getElementById('payment-error').style.display = 'block';
            
            // Scroll to payment method section
            document.querySelector('.payment-option').scrollIntoView({ 
                behavior: 'smooth', 
                block: 'center' 
            });
            
            return false;
        }
        
        // Get payment method name
        const paymentNames = {
            'cod': 'Cash on Delivery',
            'card': 'Credit/Debit Card',
            'upi': 'UPI',
            'netbanking': 'Net Banking'
        };
        
        const selectedPayment = paymentNames[paymentMethod.value];
        
        // Show success message
        alert(
            '✅ Order Placed Successfully!\n\n' +
            'Order Total: ₹<?php echo number_format($total); ?>\n' +
            'Shipping: <?php echo $shipping_options[$selected_shipping]['name']; ?>\n' +
            'Payment Method: ' + selectedPayment + '\n\n' +
            '(This is a demo - no actual payment processed)'
        );
        
        return false;
    }
</script>

<?php include 'includes/footer.php'; ?>
