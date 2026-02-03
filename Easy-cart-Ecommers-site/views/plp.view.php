<?php include 'includes/header.php'; ?>

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
                Showing
                <?php echo count($paged_products); ?> of
                <?php echo $total_items; ?> product
                <?php echo $total_items !== 1 ? 's' : ''; ?>
            </p>
        </div>

        <div class="product-grid">
            <?php if (count($paged_products) > 0): ?>
            <?php foreach ($paged_products as $product): ?>
            <div class="product-card">
                <img src="<?php echo htmlspecialchars($product['image']); ?>"
                    alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
                <div class="product-info">
                    <a href="pdp.php?id=<?php echo $product['id']; ?>" class="product-title">
                        <?php echo htmlspecialchars($product['name']); ?>
                    </a>
                    <div class="product-price">₹
                        <?php echo number_format($product['price']); ?>
                    </div>
                    <?php if(isset($product['shipping_type'])): ?>
                    <div
                        style="font-size: 0.8rem; color: <?php echo ($product['shipping_type'] === 'express') ? '#10b981' : '#f59e0b'; ?>; margin-bottom: 0.5rem; font-weight: 500;">
                        ✔
                        <?php echo ucfirst($product['shipping_type']); ?> Delivery
                    </div>
                    <?php endif; ?>
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
        <!-- pagination changes  -->
        <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php 
                // Build base URL for pagination links
                $params = $_GET;
                unset($params['page']);
                $query = http_build_query($params);
                $base_url = "plp.php?" . ($query ? $query . "&" : "");
                ?>

            <?php if ($current_page_num > 1): ?>
            <a href="<?php echo $base_url . 'page=' . ($current_page_num - 1); ?>" class="page-link">Prev</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="<?php echo $base_url . 'page=' . $i; ?>"
                class="page-link <?php echo $i === $current_page_num ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
            <?php endfor; ?>

            <?php if ($current_page_num < $total_pages): ?>
            <a href="<?php echo $base_url . 'page=' . ($current_page_num + 1); ?>" class="page-link">Next</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </section>
</div>

<?php include 'includes/footer.php'; ?>