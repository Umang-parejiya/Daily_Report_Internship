<?php
// Product Detail Page - pdp.php
session_start(); // Start session for cart functionality

$current_page = 'products';
$page_title = 'Easy-Cart - Product Details';

// Load products data
require_once 'data/products.php';

// Get product ID from URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Find the product
$product = null;
foreach ($products as $p) {
    if ($p['id'] === $product_id) {
        $product = $p;
        break;
    }
}

// Handle Add to Cart POST request
$cart_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    // Initialize cart if not exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Add product ID to cart
    if ($product) {
        $_SESSION['cart'][] = $product_id;
        $cart_message = 'Product added to cart successfully!';
    }
}

// If product not found, redirect to products page
if (!$product) {
    header('Location: plp.php');
    exit;
}

$page_title = 'Easy-Cart - ' . $product['name'];

// Include header
include 'includes/header.php';
?>

<div class="container">
    <?php if ($cart_message): ?>
        <div class="card" style="background: #4ade80; color: white; padding: 1rem; margin-bottom: 2rem; text-align: center;">
            <?php echo htmlspecialchars($cart_message); ?>
            <a href="cart.php" style="color: white; text-decoration: underline; margin-left: 1rem;">View Cart</a>
        </div>
    <?php endif; ?>

    <section class="section">
        <div class="product-detail">
            <div class="product-gallery">
                <img src="<?php echo htmlspecialchars($product['image']); ?>" 
                     alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
            
            <div class="product-content">
                <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                <div class="product-price">₹<?php echo number_format($product['price']); ?></div>
                
                <div class="product-description">
                    <p><?php echo htmlspecialchars($product['description']); ?></p>
                </div>

                <div style="margin-bottom: 1rem;">
                    <p><strong>Category:</strong> 
                        <a href="plp.php?category=<?php echo urlencode($product['category']); ?>" 
                           style="color: var(--accent);">
                            <?php echo htmlspecialchars($product['category']); ?>
                        </a>
                    </p>
                    <p><strong>Brand:</strong> 
                        <a href="plp.php?brand=<?php echo urlencode($product['brand']); ?>" 
                           style="color: var(--accent);">
                            <?php echo htmlspecialchars($product['brand']); ?>
                        </a>
                    </p>
                </div>

                <form method="POST" action="">
                    <button type="submit" name="add_to_cart" class="btn btn-primary" 
                            style="width: 100%; padding: 1rem; font-size: 1rem;">
                        Add to Cart
                    </button>
                </form>

                <a href="plp.php" class="btn btn-secondary" 
                   style="width: 100%; margin-top: 1rem; display: inline-block; text-align: center;">
                    Continue Shopping
                </a>
            </div>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>
