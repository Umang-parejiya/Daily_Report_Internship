<?php
// order_view.php - Display details for a specific order
require_once 'config/session.php';
require_once 'config/db.php';
require_once 'config/auth.php';

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = $_SESSION['user_id'];

if ($order_id <= 0) {
    header('Location: orders.php');
    exit;
}

try {
    // 1. Fetch Order Metadata (Ensure it belongs to active user)
    $stmtOrder = $pdo->prepare("
        SELECT * FROM sales_order 
        WHERE entity_id = ? AND customer_email = (SELECT email FROM users WHERE id = ?)
    ");
    $stmtOrder->execute([$order_id, $user_id]);
    $order = $stmtOrder->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        header('Location: orders.php');
        exit;
    }

    // 2. Fetch Order Items
    $stmtItems = $pdo->prepare("
        SELECT soi.*, p.image 
        FROM sales_order_item soi
        LEFT JOIN catalog_product_entity p ON soi.product_id = p.entity_id
        WHERE soi.order_id = ?
    ");
    $stmtItems->execute([$order_id]);
    $order_items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

    $page_title = "Order #" . $order['increment_id'] . " Details";
    $current_page = 'orders';

    include 'views/order_view.view.php';

} catch (PDOException $e) {
    die("Error fetching order details: " . $e->getMessage());
}
?>
