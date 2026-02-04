<?php
// Product Detail Page - pdp.php
require_once 'config/session.php';
require_once 'includes/cart_db.php';
// Helper to get current DB session key
$db_session_key = isset($_SESSION['user_id']) ? 'user_' . $_SESSION['user_id'] : $_SESSION['guest_user_id'];

$current_page = 'products';
$page_title = 'Easy-Cart - Product Details';

// Load products data
// Load Database Configuration
require_once 'config/db.php';

// Get product ID from URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product = null;

if ($product_id > 0) {
    // Fetch Main Product Data
    $stmt = $pdo->prepare("
        SELECT 
            p.entity_id as id, 
            p.name, 
            p.price, 
            p.image, 
            p.brand, 
            p.shipping_type,
            c.name as category
        FROM catalog_product_entity p
        LEFT JOIN catalog_category_product ccp ON p.entity_id = ccp.product_id
        LEFT JOIN catalog_category_entity c ON ccp.category_id = c.entity_id
        WHERE p.entity_id = :id
    ");
    $stmt->execute(['id' => $product_id]);
    $product = $stmt->fetch();

    if ($product) {
        // Fetch Description
        $stmt_desc = $pdo->prepare("SELECT value FROM catalog_product_attribute WHERE product_id = :id AND attribute_code = 'description'");
        $stmt_desc->execute(['id' => $product_id]);
        $desc = $stmt_desc->fetchColumn();
        $product['description'] = $desc ? $desc : '';

        // Fetch Gallery
        $stmt_gallery = $pdo->prepare("SELECT value FROM catalog_product_attribute WHERE product_id = :id AND attribute_code = 'gallery_item'");
        $stmt_gallery->execute(['id' => $product_id]);
        $product['gallery'] = $stmt_gallery->fetchAll(PDO::FETCH_COLUMN);
    }
}
// Check if product is in cart (Database)
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$guestUserId = isset($_SESSION['guest_user_id']) ? $_SESSION['guest_user_id'] : null;
$in_cart = false;

if ($userId) {
    $stmtCheck = $pdo->prepare("SELECT 1 FROM sales_cart c JOIN sales_cart_items ci ON c.cart_id = ci.cart_id WHERE c.user_id = ? AND ci.product_id = ? AND ci.status = 'active'");
    $stmtCheck->execute([$userId, $product_id]);
} else if ($guestUserId) {
    $stmtCheck = $pdo->prepare("SELECT 1 FROM sales_cart c JOIN sales_cart_items ci ON c.cart_id = ci.cart_id WHERE c.guest_user_id = ? AND ci.product_id = ? AND ci.status = 'active'");
    $stmtCheck->execute([$guestUserId, $product_id]);
} else {
    $stmtCheck = null;
}

if ($stmtCheck && $stmtCheck->fetch()) {
    $in_cart = true;
}

// End DB Fetch

// Handle AJAX Add to Cart
require_once 'add_to_cart.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_add'])) {
    header('Content-Type: application/json');
    $pid = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $result = handleAddToCart($pdo, $pid, 1);
    echo json_encode($result);
    exit;
}

// Handle Add to Cart POST request
$cart_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    if ($product) {
        $result = handleAddToCart($pdo, $product_id, 1);
        $cart_message = $result['message'];
    }
}

// If product not found, redirect to products page
if (!$product) {
    header('Location: plp.php');
    exit;
}

$page_title = 'Easy-Cart - ' . $product['name'];

// Include view
include 'views/pdp.view.php';
?>
