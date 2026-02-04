<?php
/**
 * cart.php
 * Overhauled to use database-only cart storage.
 */
require_once 'config/session.php';
require_once 'config/db.php';

$current_page = 'cart';
$page_title = 'Easy-Cart - My Cart';

// 1. Identify User/Guest and find Cart ID
$userId = $_SESSION['user_id'] ?? null;
$guestUserId = $_SESSION['guest_user_id'] ?? null;
$cartId = null;

if ($userId) {
    $stmt = $pdo->prepare("SELECT cart_id FROM sales_cart WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
    $stmt->execute([$userId]);
    $cartId = $stmt->fetchColumn();
} else if ($guestUserId) {
    $stmt = $pdo->prepare("SELECT cart_id FROM sales_cart WHERE guest_user_id = ? ORDER BY created_at DESC LIMIT 1");
    $stmt->execute([$guestUserId]);
    $cartId = $stmt->fetchColumn();
}

// --- HANDLE CART ACTIONS ---

// 1. Remove Item
if (isset($_GET['remove']) && $cartId) {
    $remove_id = intval($_GET['remove']);
    $pdo->prepare("DELETE FROM sales_cart_items WHERE cart_id = ? AND product_id = ? AND status = 'active'")->execute([$cartId, $remove_id]);

    if (isset($_GET['ajax_remove'])) {
        header('Content-Type: application/json');
        
        // Fetch new totals
        $stmtTotals = $pdo->prepare("SELECT SUM(quantity) as count, SUM(subtotal) as subtotal FROM sales_cart_items WHERE cart_id = ? AND status = 'active'");
        $stmtTotals->execute([$cartId]);
        $totals = $stmtTotals->fetch();
        
        $subtotal = $totals['subtotal'] ?? 0;
        $total_quantity = $totals['count'] ?? 0;
        
        // Calculate Discount
        $discount = 0;
        $discount_percentage = 0;
        if ($total_quantity > 0 && $total_quantity % 2 === 0) {
            $discount_percentage = min($total_quantity, 50);
            $discount = ($subtotal * $discount_percentage) / 100;
        }
        
        // Determine Shipping Type (Fetch remaining items)
        $stmtShipping = $pdo->prepare("SELECT p.shipping_type FROM sales_cart_items ci JOIN catalog_product_entity p ON ci.product_id = p.entity_id WHERE ci.cart_id = ? AND ci.status = 'active'");
        $stmtShipping->execute([$cartId]);
        $shipping_types = $stmtShipping->fetchAll(PDO::FETCH_COLUMN);
        
        $has_freight = in_array('freight', $shipping_types);
        $cart_type = ($has_freight || $subtotal >= 300) ? 'freight' : 'express';

        echo json_encode([
            'success' => true,
            'cartCount' => (int)$total_quantity,
            'newSubtotal' => number_format($subtotal),
            'newDiscount' => number_format($discount),
            'discountPercentage' => $discount_percentage,
            'hasDiscount' => ($discount > 0),
            'newTotal' => number_format($subtotal - $discount),
            'cartType' => ucfirst($cart_type)
        ]);
        exit;
    }
    header('Location: cart.php');
    exit;
}

// 2. Update Quantity (Increase/Decrease)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['product_id']) && $cartId) {
    $action = $_POST['action'];
    $pid = intval($_POST['product_id']);
    
    if ($action === 'increase') {
        $pdo->prepare("UPDATE sales_cart_items SET quantity = quantity + 1, subtotal = price * (quantity + 1) WHERE cart_id = ? AND product_id = ? AND status = 'active'")->execute([$cartId, $pid]);
    } else if ($action === 'decrease') {
        $pdo->prepare("UPDATE sales_cart_items SET quantity = GREATEST(0, quantity - 1), subtotal = price * GREATEST(0, quantity - 1) WHERE cart_id = ? AND product_id = ? AND status = 'active'")->execute([$cartId, $pid]);
        // Remove item if quantity reached 0
        $pdo->prepare("DELETE FROM sales_cart_items WHERE cart_id = ? AND product_id = ? AND quantity = 0 AND status = 'active'")->execute([$cartId, $pid]);
    }

    if (isset($_POST['ajax_update'])) {
        header('Content-Type: application/json');
        
        // Fetch specific item qty
        $stmtItem = $pdo->prepare("SELECT quantity FROM sales_cart_items WHERE cart_id = ? AND product_id = ? AND status = 'active'");
        $stmtItem->execute([$cartId, $pid]);
        $new_qty = $stmtItem->fetchColumn() ?: 0;

        // Fetch overall totals
        $stmtTotals = $pdo->prepare("SELECT SUM(quantity) as count, SUM(subtotal) as subtotal FROM sales_cart_items WHERE cart_id = ? AND status = 'active'");
        $stmtTotals->execute([$cartId]);
        $totals = $stmtTotals->fetch();
        
        $subtotal = $totals['subtotal'] ?? 0;
        $total_quantity = $totals['count'] ?? 0;
        
        // Calculate Discount
        $discount = 0;
        $discount_percentage = 0;
        if ($total_quantity > 0 && $total_quantity % 2 === 0) {
            $discount_percentage = min($total_quantity, 50);
            $discount = ($subtotal * $discount_percentage) / 100;
        }
        
        // Determine Shipping Type
        $stmtShipping = $pdo->prepare("SELECT p.shipping_type FROM sales_cart_items ci JOIN catalog_product_entity p ON ci.product_id = p.entity_id WHERE ci.cart_id = ?");
        $stmtShipping->execute([$cartId]);
        $shipping_types = $stmtShipping->fetchAll(PDO::FETCH_COLUMN);
        
        $has_freight = in_array('freight', $shipping_types);
        $cart_type = ($has_freight || $subtotal >= 300) ? 'freight' : 'express';

        echo json_encode([
            'success' => true,
            'productId' => $pid,
            'newQty' => (int)$new_qty,
            'newSubtotal' => number_format($subtotal),
            'newDiscount' => number_format($discount),
            'discountPercentage' => $discount_percentage,
            'hasDiscount' => ($discount > 0),
            'newTotal' => number_format($subtotal - $discount),
            'cartType' => ucfirst($cart_type),
            'removed' => ($new_qty === 0)
        ]);
        exit;
    }
    header('Location: cart.php');
    exit;
}

// --- PREPARE DATA FOR DISPLAY ---
$cart_items = [];
$subtotal = 0;
$total_quantity = 0;
$has_freight = false;

if ($cartId) {
    $stmtItems = $pdo->prepare("
        SELECT ci.*, p.shipping_type, p.image as display_image, b.name as brand_name
        FROM sales_cart_items ci
        JOIN catalog_product_entity p ON ci.product_id = p.entity_id
        LEFT JOIN brands b ON ci.brand_id = b.id
        WHERE ci.cart_id = ? AND ci.status = 'active'
    ");
    $stmtItems->execute([$cartId]);
    $db_items = $stmtItems->fetchAll();

    foreach ($db_items as $item) {
        $cart_items[] = [
            'id' => $item['product_id'],
            'name' => $item['product_name'],
            'price' => $item['price'],
            'image' => $item['image_path'] ?: $item['display_image'],
            'brand' => $item['brand_name'] ?: 'Generic',
            'qty' => $item['quantity'],
            'total' => $item['subtotal'],
            'shipping_type' => $item['shipping_type']
        ];
        $subtotal += $item['subtotal'];
        $total_quantity += $item['quantity'];
        if ($item['shipping_type'] === 'freight') {
            $has_freight = true;
        }
    }
}

// Calculate Discount
$discount = 0;
$discount_percentage = 0;
if ($total_quantity > 0 && $total_quantity % 2 === 0) {
    $discount_percentage = min($total_quantity, 50);
    $discount = ($subtotal * $discount_percentage) / 100;
}

// Determine Cart Type
$cart_type = ($has_freight || $subtotal >= 300) ? 'freight' : 'express';
$_SESSION['cart_type'] = $cart_type;
$total = $subtotal - $discount;

include 'views/cart.view.php';
?>
