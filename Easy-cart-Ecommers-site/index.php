<?php
// Home Page - index.php
$current_page = 'home';
$page_title = 'Easy-Cart - Home';

// Load data files
require_once 'data/products.php';
require_once 'data/categories.php';
require_once 'data/brands.php';

// Get featured products (first 4 products)
$featured_products = array_slice($products, 0, 4);

// Include header
include 'includes/header.php';
?>

<div class="container">
    <section class="section">
        <div class="section-header">
            <h1 class="section-title">Welcome to Easy-Cart</h1>
            <p class="section-subtitle">Your one-stop shop for all your shopping needs. Discover amazing products at great prices!</p>
        </div>
    </section>

    <section class="section">
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
                        <a href="pdp.php?id=<?php echo $product['id']; ?>" class="btn btn-secondary">View Details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="section">
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
