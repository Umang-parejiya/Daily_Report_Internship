<?php
session_start();
$current_page = 'cart';
$page_title = 'Easy-Cart - My Cart';

require_once 'data/products.php';

// --- HANDLE CART ACTIONS ---

// 1. Remove Item
if (isset($_GET['remove'])) {
    $remove_id = intval($_GET['remove']);
    // Remove all instances of this ID
    if (isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array_filter($_SESSION['cart'], function($id) use ($remove_id) {
            return $id != $remove_id;
        });
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index
    }
    header('Location: cart.php');
    exit;
}

// 2. Update Quantity (Add/Subtract) - JSON/AJAX Support
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['product_id'])) {
    $action = $_POST['action'];
    $pid = intval($_POST['product_id']);
    
    // Perform modification
    if ($action === 'increase') {
        $_SESSION['cart'][] = $pid;
    } elseif ($action === 'decrease') {
        if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $key = array_search($pid, $_SESSION['cart']);
        if ($key !== false) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); 
        }
    }

    // If AJAX request, return new state
    if (isset($_POST['ajax_update'])) {
        header('Content-Type: application/json');
        
        // Recalculate totals
        $cart_counts = array_count_values($_SESSION['cart']);
        $new_qty = isset($cart_counts[$pid]) ? $cart_counts[$pid] : 0;
        
        // Calculate totals
        $subtotal = 0;
        foreach ($cart_counts as $id => $qty) {
            foreach ($products as $p) {
                if ($p['id'] === $id) {
                    $subtotal += $p['price'] * $qty;
                }
            }
        }
        
        echo json_encode([
            'success' => true,
            'productId' => $pid,
            'newQty' => $new_qty,
            'newSubtotal' => number_format($subtotal),
            'newTotal' => number_format($subtotal), // Assuming free shipping for update immediate feedback or recalc later
            'removed' => ($new_qty === 0)
        ]);
        exit;
    }

    // Fallback for non-AJAX
    header('Location: cart.php');
    exit;
}

// --- PREPARE DATA FOR DISPLAY ---

// Initialize cart array if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Count quantities: [1 => 2, 4 => 1] (Product ID => Qty)
$cart_counts = array_count_values($_SESSION['cart']);

// Build rich item list
$cart_items = [];
$subtotal = 0;

foreach ($cart_counts as $product_id => $quantity) {
    // Find product details
    foreach ($products as $p) {
        if ($p['id'] === $product_id) {
            $line_total = $p['price'] * $quantity;
            $subtotal += $line_total;
            
            $cart_items[] = [
                'id' => $p['id'],
                'name' => $p['name'],
                'price' => $p['price'],
                'image' => $p['image'],
                'brand' => $p['brand'],
                'qty' => $quantity,
                'total' => $line_total
            ];
            break; 
        }
    }
}

// Total (shipping calculated at checkout)
$total = $subtotal;

include 'includes/header.php';
?>

<div class="container">
    <section class="section">
        
        <?php if (count($cart_items) > 0): ?>
            
            <div class="section-header">
                <h1 class="section-title">Your Shopping Cart</h1>
                <p class="section-subtitle">
                    You have <?php echo count($_SESSION['cart']); ?> item(s) in your cart
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
                             alt="<?php echo htmlspecialchars($item['name']); ?>" 
                             class="cart-item-image">
                        
                        <!-- Details -->
                        <div class="cart-item-details">
                            <div>
                                <div class="cart-item-header">
                                    <h3 class="cart-item-title">
                                        <a href="pdp.php?id=<?php echo $item['id']; ?>" style="color: inherit; text-decoration: none;">
                                            <?php echo htmlspecialchars($item['name']); ?>
                                        </a>
                                    </h3>
                                    <div class="cart-item-price" data-price="<?php echo $item['price']; ?>">
                                        ₹<?php echo number_format($item['price']); ?>
                                    </div>
                                </div>
                                <div class="cart-item-brand">
                                    Brand: <?php echo htmlspecialchars($item['brand']); ?>
                                </div>
                            </div>

                            <div class="cart-item-actions">
                                <!-- Interactive Qty Control -->
                                <form method="POST" class="qty-control">
                                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                    
                                    <button type="submit" name="action" value="decrease" class="qty-btn" title="Decrease">
                                        −
                                    </button>
                                    
                                    <div class="qty-display"><?php echo $item['qty']; ?></div>
                                    
                                    <button type="submit" name="action" value="increase" class="qty-btn" title="Increase">
                                        +
                                    </button>
                                </form>

                                <!-- Remove Button -->
                                <a href="cart.php?remove=<?php echo $item['id']; ?>" 
                                   class="remove-btn">
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
                                        <td style="padding: 0.5rem 0; border: none; color: var(--text-secondary);">Subtotal</td>
                                        <td style="padding: 0.5rem 0; border: none; text-align: right; font-weight: 600;">
                                            ₹<?php echo number_format($subtotal); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 0.5rem 0; border: none; color: var(--text-secondary);">Delivery Chagres</td>
                                        <td style="padding: 0.5rem 0; border: none; text-align: right; font-style: italic; color: var(--text-secondary);">
                                            Calculated at checkout
                                        </td>
                                    </tr>
                                    <tr style="border-top: 2px solid var(--border);">
                                        <td style="padding: 1rem 0; border: none;"><strong style="font-size: 1.1rem;">Total Est.</strong></td>
                                        <td style="padding: 1rem 0; border: none; text-align: right;"><strong style="font-size: 1.1rem; color: var(--accent);">₹<?php echo number_format($total); ?></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <a href="checkout.php" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 1rem;">
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
