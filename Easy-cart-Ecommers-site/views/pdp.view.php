<?php include 'includes/header.php'; ?>

<div class="container">
    <?php if ($cart_message): ?>
    <div class="card"
        style="background: #4ade80; color: white; padding: 1rem; margin-bottom: 2rem; text-align: center;">
        <?php echo htmlspecialchars($cart_message); ?>
        <a href="cart.php" style="color: white; text-decoration: underline; margin-left: 1rem;">View Cart</a>
    </div>
    <?php endif; ?>

    <section class="section">
        <div class="product-detail">
            <div class="product-gallery">
                <div class="main-image-container">
                    <img src="<?php echo htmlspecialchars($product['image']); ?>"
                        alt="<?php echo htmlspecialchars($product['name']); ?>" class="main-image">
                </div>

                <?php if (isset($product['gallery']) && !empty($product['gallery'])): ?>
                <div class="thumbnails">
                    <div class="thumb-item active">
                        <img src="<?php echo htmlspecialchars($product['image']); ?>"
                            alt="<?php echo htmlspecialchars($product['name']); ?>" class="thumb-img">
                    </div>
                    <?php foreach ($product['gallery'] as $thumb): ?>
                    <div class="thumb-item">
                        <img src="<?php echo htmlspecialchars($thumb); ?>"
                            alt="<?php echo htmlspecialchars($product['name']); ?>" class="thumb-img">
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <div class="product-content">
                <div id="pdp-success-banner" class="pdp-success-banner" style="display: none;">
                    <span>Your product has been added successfully</span>
                    <a href="cart.php" class="btn-view-cart">View Cart</a>
                </div>
                <h1>
                    <?php echo htmlspecialchars($product['name']); ?>
                </h1>
                <div class="product-price">₹
                    <?php echo number_format($product['price']); ?>
                </div>

                <div class="product-description">
                    <p>
                        <?php echo htmlspecialchars($product['description']); ?>
                    </p>
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
                    <?php if(isset($product['shipping_type'])): ?>
                    <p style="margin-top: 0.5rem;">
                        <span
                            style="font-weight: 600; color: <?php echo ($product['shipping_type'] === 'express') ? '#10b981' : '#f59e0b'; ?>;">
                            ✔
                            <?php echo ucfirst($product['shipping_type']); ?> Delivery
                        </span>
                    </p>
                    <?php endif; ?>
                </div>

                <form method="POST" action="">
                    <button type="submit" name="add_to_cart" class="btn btn-primary"
                        style="width: 100%; padding: 1rem; font-size: 1rem;">
                        <?php 
                            // Check if product is in cart
                            $in_cart = false;
                            if (isset($_SESSION['cart']) && in_array($product['id'], $_SESSION['cart'])) {
                                $in_cart = true;
                            }
                            echo $in_cart ? 'More Item Add' : 'Add to Cart'; 
                        ?>
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