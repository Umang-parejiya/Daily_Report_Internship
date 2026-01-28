<?php
// Checkout Page - checkout.php
session_start();

$current_page = 'cart';
$page_title = 'Easy-Cart - Checkout';

// Load products data
require_once 'data/products.php';

// Handle Order Creation (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_place_order'])) {
    header('Content-Type: application/json');
    
    // Simulate Order ID
    $order_id = mt_rand(100000, 999999);
    
    // Calculate total again for security
    $cart_product_ids = array_count_values($_SESSION['cart']);
    $order_items = [];
    $order_total = 0;
    
    foreach ($cart_product_ids as $pid => $qty) {
        foreach ($products as $p) {
            if ($p['id'] === $pid) {
                $line_total = $p['price'] * $qty;
                $order_total += $line_total;
                $order_items[] = [
                    'product_id' => $pid,
                    'quantity' => $qty,
                    'price' => $p['price']
                ];
                break;
            }
        }
    }
    
    // Calculate total quantity for discount quantity
    $total_quantity = 0;
    foreach ($order_items as $item) {
        $total_quantity += $item['quantity'];
    }

    // Calculate discount if quantity is even
    $discount = 0;
    if ($total_quantity > 0 && $total_quantity % 2 === 0) {
        $discount_percentage = min($total_quantity, 50);
        $discount = ($order_total * $discount_percentage) / 100;
    }

    // Add Shipping (Simplified based on POST or default)
    $shipping_cost = isset($_POST['shipping_cost']) ? floatval($_POST['shipping_cost']) : 0;
    $final_total = $order_total - $discount + $shipping_cost;
    
    // Create Order Object
    $new_order = [
        'order_id' => $order_id,
        'date' => date('Y-m-d'),
        'status' => 'Processing',
        'total' => $final_total,
        'items' => $order_items
    ];
    
    // Save to Session (Mock Database)
    if (!isset($_SESSION['orders'])) {
        $_SESSION['orders'] = [];
    }
    // Prepend new order
    array_unshift($_SESSION['orders'], $new_order);
    
    // Clear Cart
    unset($_SESSION['cart']);
    
    echo json_encode([
        'success' => true,
        'order_id' => $order_id,
        'redirect' => 'orders.php'
    ]);
    exit;
}

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
                'product' => array_merge($product, ['image' => $product['image']]),
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
        'delivery' => '6-7 business days',
        'icon' => '🚚'
    ],
    'standard' => [
        'name' => 'Standard', 
        'cost' => 1000,
        'delivery' => '3-4 business days',
        'icon' => '📦'
    ],
    'express' => [
        'name' => 'Express', 
        'cost' => 2000,
        'delivery' => 'Next day delivery',
        'icon' => '🚀'
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

// Calculate total quantity for discount
$total_quantity = 0;
foreach ($cart_items as $item) {
    $total_quantity += $item['quantity'];
}

// Calculate discount if quantity is even
$discount = 0;
$discount_percentage = 0;
if ($total_quantity > 0 && $total_quantity % 2 === 0) {
    $discount_percentage = min($total_quantity, 50);   // max 50% discount
    $discount = ($subtotal * $discount_percentage) / 100;
}

// Calculate total
$total = $subtotal - $discount + $shipping;

// Include header
include 'includes/header.php';
?>

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
                                <input type="email" class="form-input" placeholder="john@example.com" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-input" placeholder="John" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-input" placeholder="Doe" required>
                            </div>
                            <div class="form-group full-width">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-input" placeholder="123 Main St" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">City</label>
                                <input type="text" class="form-input" placeholder="New York" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Postal Code</label>
                                <input type="text" class="form-input" placeholder="10001" required>
                            </div>
                            <div class="form-group full-width">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" class="form-input" placeholder="+1 (555) 000-0000" required>
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
                            <label class="selection-card">
                                <input type="radio" name="shipping_method" value="<?php echo $key; ?>" 
                                       <?php echo ($selected_shipping === $key) ? 'checked' : ''; ?>>
                                <div class="card-content">
                                    <div class="card-icon"><?php echo $option['icon']; ?></div>
                                    <div class="card-title"><?php echo $option['name']; ?></div>
                                    <div class="card-desc"><?php echo $option['delivery']; ?></div>
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
                    <p id="payment-error" style="color: var(--error); font-size: 0.875rem; margin-top: 1rem; display: none;">
                        ⚠️ Please select a payment method to continue
                    </p>
                </div>
                
                <button class="btn btn-primary" style="width: 100%; padding: 1rem; font-size: 1rem;">
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
                        <div style="display: flex; gap: 1rem; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border);">
                            <div style="width: 60px; height: 60px; background: var(--bg-accent); border-radius: var(--radius-sm); overflow: hidden;">
                                <img src="<?php echo htmlspecialchars($item['product']['image']); ?>" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 600; font-size: 0.9rem; line-height: 1.3; margin-bottom: 0.25rem;">
                                    <?php echo htmlspecialchars($item['product']['name']); ?>
                                </div>
                                <div style="font-size: 0.85rem; color: var(--text-secondary);">
                                    Qty: <?php echo $item['quantity']; ?>
                                </div>
                            </div>
                            <div style="font-weight: 600;">
                                ₹<?php echo number_format($item['subtotal']); ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Totals -->
                    <div style="border-top: 2px solid var(--border); padding-top: 1rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span style="color: var(--text-secondary);">Subtotal</span>
                            <span id="subtotal-value">₹<?php echo number_format($subtotal); ?></span>
                        </div>
                            <!-- Calculate total quantity for discount quantity -->
                        <?php if ($discount > 0): ?>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span style="color: var(--success);">Discount (<?php echo $discount_percentage; ?>% off based on even quantity)</span>
                            <span id="discount-value" style="color: var(--success);" data-value="<?php echo $discount; ?>">
                                -₹<?php echo number_format($discount); ?>
                            </span>
                        </div>
                        <?php endif; ?>

                        <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                            <span style="color: var(--text-secondary);">Shipping</span>
                            <span id="shipping-value" style="<?php echo ($shipping == 0) ? 'color: var(--success);' : ''; ?>">
                                <?php echo ($shipping == 0) ? 'FREE' : '₹' . number_format($shipping); ?>
                            </span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-top: 1rem; padding-top: 1rem; border-top: 1px dashed var(--border);">
                            <span style="font-size: 1.25rem; font-weight: 700;">Total</span>
                            <span id="total-value" style="font-size: 1.25rem; font-weight: 700; color: var(--accent);">
                                ₹<?php echo number_format($total); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>



<?php include 'includes/footer.php'; ?>
