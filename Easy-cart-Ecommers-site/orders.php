<?php
// My Orders Page - orders.php
require_once 'config/session.php';
require_once 'includes/cart_db.php';
require_once 'config/auth.php';
require_once 'config/db.php';

$current_page = 'orders';
$page_title = 'Easy-Cart - My Orders';

// --- HANDLE ACTIONS ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_order'])) {
    $cancel_id = intval($_POST['order_id']);
    
    try {
        // Update to use sales_order and entity_id
        $stmt = $pdo->prepare("UPDATE sales_order SET status = 'cancelled' WHERE entity_id = ? AND customer_email = (SELECT email FROM users WHERE id = ?) AND status = 'processing'");
        $stmt->execute([$cancel_id, $_SESSION['user_id']]);
    } catch (PDOException $e) {
        // Handle error silently or log
    }
    
    // Redirect to prevent resubmission
    header('Location: orders.php');
    exit;
}

// --- DATA SOURCE ---
// Fetch Orders from DB (sales_order)
try {
    $stmt = $pdo->prepare("
        SELECT 
            entity_id as order_id, 
            increment_id,
            created_at as date, 
            status, 
            shipping_method as shipping_type,
            payment_method,
            subtotal,
            shipping_amount,
            tax_amount,
            grand_total as total 
        FROM sales_order 
        WHERE customer_email = (SELECT email FROM users WHERE id = ?) 
        ORDER BY created_at DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $my_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch items for each order
    foreach ($my_orders as &$order) {
        $stmtItems = $pdo->prepare("SELECT product_id, name, price, quantity FROM sales_order_item WHERE order_id = ?");
        $stmtItems->execute([$order['order_id']]);
        $order['items'] = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Fetch products for images (for existing view logic)
    $stmtProd = $pdo->query("SELECT entity_id as id, name, image FROM catalog_product_entity");
    $products = $stmtProd->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $my_orders = [];
    $products = [];
}

// Include view
include 'views/orders.view.php';
?>
