<?php
// My Orders Page - orders.php
$current_page = 'orders';
$page_title = 'Easy-Cart - My Orders';

// Load orders and products data
require_once 'data/orders.php';
require_once 'data/products.php';

// Include header
include 'includes/header.php';
?>

<div class="container">
    <section class="section">
        <div class="section-header">
            <h1 class="section-title">My Orders</h1>
            <p class="section-subtitle">Track and manage your orders</p>
        </div>

        <?php if (count($orders) > 0): ?>
            <?php foreach ($orders as $order): ?>
                <div class="card" style="margin-bottom: 2rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border);">
                        <div>
                            <h3 style="margin-bottom: 0.5rem;">Order #<?php echo htmlspecialchars($order['order_id']); ?></h3>
                            <p style="color: var(--text-secondary); font-size: 0.875rem;">
                                Placed on <?php echo date('F j, Y', strtotime($order['date'])); ?>
                            </p>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-size: 1.25rem; font-weight: 700; color: var(--accent);">
                                ₹<?php echo number_format($order['total']); ?>
                            </div>
                            <div style="margin-top: 0.5rem;">
                                <span style="padding: 0.25rem 0.75rem; border-radius: 4px; font-size: 0.875rem; font-weight: 600;
                                    <?php 
                                    if ($order['status'] === 'Delivered') {
                                        echo 'background: #d1fae5; color: #065f46;';
                                    } elseif ($order['status'] === 'Shipped') {
                                        echo 'background: #dbeafe; color: #1e40af;';
                                    } else {
                                        echo 'background: #fef3c7; color: #92400e;';
                                    }
                                    ?>">
                                    <?php echo htmlspecialchars($order['status']); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order['items'] as $item): ?>
                                    <?php
                                    // Find product name
                                    $product_name = 'Unknown Product';
                                    foreach ($products as $product) {
                                        if ($product['id'] === $item['product_id']) {
                                            $product_name = $product['name'];
                                            break;
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($product_name); ?></td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td>₹<?php echo number_format($item['price']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="card" style="text-align: center; padding: 3rem;">
                <h3>No orders yet</h3>
                <p>Start shopping to see your orders here!</p>
                <a href="plp.php" class="btn btn-primary" style="margin-top: 1rem;">
                    Browse Products
                </a>
            </div>
        <?php endif; ?>
    </section>
</div>

<?php include 'includes/footer.php'; ?>
