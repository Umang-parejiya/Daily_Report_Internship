<?php
// Shopping Cart Page - cart.php
session_start(); // Start session

$current_page = 'cart';
$page_title = 'Easy-Cart - Shopping Cart';

// Load products data
require_once 'data/products.php';

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle remove from cart
if (isset($_GET['remove'])) {
    $remove_id = intval($_GET['remove']);
    // Find and remove the first occurrence of this product ID
    $key = array_search($remove_id, $_SESSION['cart']);
    if ($key !== false) {
        unset($_SESSION['cart'][$key]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index array
    }
    header('Location: cart.php');
    exit;
}

// Get cart items with quantities
$cart_items = [];
$cart_product_ids = array_count_values($_SESSION['cart']);

foreach ($cart_product_ids as $product_id => $quantity) {
    // Find product details
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

// Calculate totals
$subtotal = 0;
foreach ($cart_items as $item) {
    $subtotal += $item['subtotal'];
}
// Shipping will be selected at checkout
$total = $subtotal;

// Include header
include 'includes/header.php';
?>

<div class="container">
    <section class="section">
        <div class="section-header">
            <h1 class="section-title">Shopping Cart</h1>
            <p class="section-subtitle">
                <?php 
                $item_count = count($_SESSION['cart']);
                echo $item_count > 0 ? "You have $item_count item(s) in your cart" : "Your cart is empty";
                ?>
            </p>
        </div>

        <?php if (count($cart_items) > 0): ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item): ?>
                            <tr>
                                <td>
                                    <a href="pdp.php?id=<?php echo $item['product']['id']; ?>" 
                                       style="color: var(--accent);">
                                        <?php echo htmlspecialchars($item['product']['name']); ?>
                                    </a>
                                </td>
                                <td>₹<?php echo number_format($item['product']['price']); ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td>₹<?php echo number_format($item['subtotal']); ?></td>
                                <td>
                                    <a href="cart.php?remove=<?php echo $item['product']['id']; ?>" 
                                       class="btn btn-ghost" 
                                       style="padding: 0.5rem 1rem; font-size: 0.875rem;"
                                       onclick="return confirm('Remove this item from cart?');">
                                        Remove
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="card price-summary">
                <h3>Price Summary</h3>
                <div class="table-container">
                    <table>
                        <tbody>
                            <tr>
                                <td>Subtotal</td>
                                <td>₹<?php echo number_format($subtotal); ?></td>
                            </tr>
                            <tr>
                                <td>Shipping</td>
                                <td><em>Calculated at checkout</em></td>
                            </tr>
                            <tr>
                                <td><strong>Subtotal</strong></td>
                                <td><strong>₹<?php echo number_format($total); ?></strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <a href="checkout.php" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
                    Proceed to Checkout
                </a>
                <a href="plp.php" class="btn btn-secondary" style="width: 100%; margin-top: 0.5rem;">
                    Continue Shopping
                </a>
            </div>
        <?php else: ?>
            <div class="card" style="text-align: center; padding: 3rem;">
                <h3>Your cart is empty</h3>
                <p>Start adding products to your cart!</p>
                <a href="plp.php" class="btn btn-primary" style="margin-top: 1rem;">
                    Browse Products
                </a>
            </div>
        <?php endif; ?>
    </section>
</div>

<?php include 'includes/footer.php'; ?>
