<?php
// Product Listing Page - plp.php
$current_page = 'products';
$page_title = 'Easy-Cart - All Products';

// Load products data
require_once 'data/products.php';

// Get filter parameters from URL
$filter_category = isset($_GET['category']) ? $_GET['category'] : null;
$filter_brand = isset($_GET['brand']) ? $_GET['brand'] : null;

// Filter products based on URL parameters
$filtered_products = $products;

if ($filter_category) {
    $filtered_products = array_filter($filtered_products, function($product) use ($filter_category) {
        return $product['category'] === $filter_category;
    });
    $page_title = 'Easy-Cart - ' . htmlspecialchars($filter_category);
}

if ($filter_brand) {
    $filtered_products = array_filter($filtered_products, function($product) use ($filter_brand) {
        return $product['brand'] === $filter_brand;
    });
    $page_title = 'Easy-Cart - ' . htmlspecialchars($filter_brand) . ' Products';
}

// Include header
include 'includes/header.php';
?>

<div class="container">
    <section class="section">
        <div class="section-header">
            <h1 class="section-title">
                <?php 
                if ($filter_category) {
                    echo htmlspecialchars($filter_category);
                } elseif ($filter_brand) {
                    echo htmlspecialchars($filter_brand) . ' Products';
                } else {
                    echo 'All Products';
                }
                ?>
            </h1>
            <p class="section-subtitle">
                <?php 
                if ($filter_category || $filter_brand) {
                    echo 'Showing ' . count($filtered_products) . ' product(s)';
                } else {
                    echo 'Discover our complete collection';
                }
                ?>
            </p>
        </div>

        <div class="product-grid">
            <?php if (count($filtered_products) > 0): ?>
                <?php foreach ($filtered_products as $product): ?>
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
            <?php else: ?>
                <div class="card" style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
                    <h3>No products found</h3>
                    <p>Try browsing other categories or brands.</p>
                    <a href="plp.php" class="btn btn-primary" style="margin-top: 1rem;">View All Products</a>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>
