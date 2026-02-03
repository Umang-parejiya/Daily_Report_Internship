<?php include 'includes/header.php'; ?>

<div class="container">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <div class="hero-text">
                <h1 class="hero-title">Welcome to <span class="hero-accent">Easy-Cart</span></h1>
                <p class="hero-description">Your one-stop shop for all your shopping needs. Discover amazing products at great prices with fast delivery and exceptional service.</p>
                <div class="hero-actions">
                    <a href="#featured-products" class="btn btn-primary hero-cta">Shop Now</a>
                    <a href="#categories" class="btn btn-secondary hero-secondary">Explore Categories</a>
                </div>
            </div>
            <div class="hero-visual">
                <div class="hero-stats">
                    <div class="stat-item">
                        <div class="stat-number">10K+</div>
                        <div class="stat-label">Products</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">50K+</div>
                        <div class="stat-label">Happy Customers</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Support</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-background">
            <div class="hero-shape shape-1"></div>
            <div class="hero-shape shape-2"></div>
            <div class="hero-shape shape-3"></div>
        </div>
    </section>

    <section id="featured-products" class="section">
        <div class="section-header">
            <h2 class="section-title">Featured Products</h2>
            <p class="section-subtitle">Handpicked items just for you</p>
        </div>

        <div class="product-grid">
            <?php foreach ($featured_products as $product): ?>
                <div class="product-card">
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" 
                         alt="<?php echo htmlspecialchars($product['name']); ?>" 
                         class="product-image">
                    <div class="product-info">
                        <a href="pdp.php?id=<?php echo $product['id']; ?>" class="product-title">
                            <?php echo htmlspecialchars($product['name']); ?>
                        </a>
                        <div class="product-price">₹<?php echo number_format($product['price']); ?></div>
                        <?php if(isset($product['shipping_type'])): ?>
                            <div style="font-size: 0.8rem; color: <?php echo ($product['shipping_type'] === 'express') ? '#10b981' : '#f59e0b'; ?>; margin-bottom: 0.5rem; font-weight: 500;">
                                ✔ <?php echo ucfirst($product['shipping_type']); ?> Delivery
                            </div>
                        <?php endif; ?>
                        <a href="pdp.php?id=<?php echo $product['id']; ?>" class="btn btn-secondary">View Details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="categories" class="section">
        <div class="section-header">
            <h2 class="section-title">Popular Categories</h2>
            <p class="section-subtitle">Explore our wide range of categories</p>
        </div>

        <div class="category-grid">
            <?php 
            // Display first 5 categories
            $display_categories = array_slice($categories, 0, 5);
            foreach ($display_categories as $category): 
            ?>
                <a href="plp.php?category=<?php echo urlencode($category['name']); ?>" class="card">
                    <h3><?php echo htmlspecialchars($category['name']); ?></h3>
                    <p><?php 
                        // Dynamic descriptions based on category
                        $descriptions = [
                            'Electronics' => 'Latest gadgets and tech',
                            'Clothing' => 'Trendy fashion for everyone',
                            'Home & Kitchen' => 'Everything for your home',
                            'Sports & Outdoors' => 'Gear up for adventure',
                            'Books' => 'Knowledge and entertainment'
                        ];
                        echo $descriptions[$category['name']] ?? 'Explore this category';
                    ?></p>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="section">
        <div class="section-header">
            <h2 class="section-title">Popular Brands</h2>
            <p class="section-subtitle">Trusted brands you love</p>
        </div>

        <div class="brand-grid">
            <?php 
            // Display first 5 brands
            $display_brands = array_slice($brands, 0, 5);
            foreach ($display_brands as $brand): 
            ?>
                <a href="plp.php?brand=<?php echo urlencode($brand['name']); ?>" 
                   class="brand-card" 
                   data-brand="<?php echo strtolower($brand['name']); ?>">
                    <img src="<?php echo htmlspecialchars($brand['logo']); ?>" 
                         alt="<?php echo htmlspecialchars($brand['name']); ?> Logo">
                    <span><?php echo htmlspecialchars($brand['name']); ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>
