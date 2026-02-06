<?php
/**
 * add_to_cart.php
 * Handles adding products to the database-stored cart.
 * Supports both AJAX and standard POST requests.
 */

require_once 'config/db.php';
require_once 'config/session.php';

function handleAddToCart($pdo, $productId, $quantity = 1) {
    // 1. User Identification
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $guestUserId = isset($_SESSION['guest_user_id']) ? $_SESSION['guest_user_id'] : null;

    if (!$userId && !$guestUserId) {
        // Fallback if session.php failed to generate guest ID
        $_SESSION['guest_user_id'] = 'guest_' . bin2hex(random_bytes(8));
        $guestUserId = $_SESSION['guest_user_id'];
    }

    // 2. Fetch complete product data from database
    $stmt = $pdo->prepare("
        SELECT p.*, b.id as brand_id 
        FROM catalog_product_entity p
        LEFT JOIN brands b ON p.brand = b.name
        WHERE p.entity_id = ?
    ");
    $stmt->execute([$productId]);
    $product = $stmt->fetch();

    if (!$product) {
        return ['success' => false, 'message' => 'Product not found'];
    }

    // Fetch attributes for attribute_json
    $stmtAttr = $pdo->prepare("SELECT attribute_code, value FROM catalog_product_attribute WHERE product_id = ?");
    $stmtAttr->execute([$productId]);
    $attributes = $stmtAttr->fetchAll(PDO::FETCH_KEY_PAIR);
    $attributeJson = json_encode($attributes);

    // 3. Cart Table Logic
    // Find existing cart for user/guest
    if ($userId) {
        $stmtCart = $pdo->prepare("SELECT cart_id FROM sales_cart WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
        $stmtCart->execute([$userId]);
    } else {
        $stmtCart = $pdo->prepare("SELECT cart_id FROM sales_cart WHERE guest_user_id = ? ORDER BY created_at DESC LIMIT 1");
        $stmtCart->execute([$guestUserId]);
    }
    
    $cartId = $stmtCart->fetchColumn();

    if (!$cartId) {
        // Create new cart
        $stmtNewCart = $pdo->prepare("INSERT INTO sales_cart (user_id, guest_user_id) VALUES (?, ?)");
        $stmtNewCart->execute([$userId, $guestUserId]);
        $cartId = $pdo->lastInsertId();
    }


    // 4. Insert Into Cart Items / Handle Duplicates
    $stmtCheckItem = $pdo->prepare("SELECT item_id, quantity FROM sales_cart_items WHERE cart_id = ? AND product_id = ? AND status = 'active'");
    $stmtCheckItem->execute([$cartId, $productId]);
    $existingItem = $stmtCheckItem->fetch();

    if ($existingItem) {
        // Update quantity
        $newQuantity = $existingItem['quantity'] + $quantity;
        $newSubtotal = $product['price'] * $newQuantity;
        
        $stmtUpdate = $pdo->prepare("UPDATE sales_cart_items SET quantity = ?, subtotal = ? WHERE item_id = ?");
        $stmtUpdate->execute([$newQuantity, $newSubtotal, $existingItem['item_id']]);
    } else {
        // Insert new item
        $subtotal = $product['price'] * $quantity;
        $stmtInsert = $pdo->prepare("
            INSERT INTO sales_cart_items (
                cart_id, product_id, product_name, price, quantity, 
                brand_id, image_path, attribute_json, subtotal
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmtInsert->execute([
            $cartId, 
            $productId, 
            $product['name'], 
            $product['price'], 
            $quantity,
            $product['brand_id'], 
            $product['image'], 
            $attributeJson, 
            $subtotal
        ]);
    }

    // 5. Store in Session for Guest Users
    if (!$userId && $guestUserId) {
        // Initialize session cart if not exists
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        // Update session cart with product_id and quantity
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }
    }

    // Get total items count for the cart
    $stmtCount = $pdo->prepare("SELECT SUM(quantity) FROM sales_cart_items WHERE cart_id = ?");
    $stmtCount->execute([$cartId]);
    $totalCount = $stmtCount->fetchColumn();

    return [
        'success' => true, 
        'cartCount' => $totalCount, 
        'message' => 'Product added to cart successfully!'
    ];
}

// Handle Direct Request (if add_to_cart.php is called via AJAX)
if (basename($_SERVER['PHP_SELF']) == 'add_to_cart.php') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

        if ($productId > 0) {
            $result = handleAddToCart($pdo, $productId, $quantity);
            if (isset($_POST['ajax_add']) || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
                header('Content-Type: application/json');
                echo json_encode($result);
                exit;
            } else {
                // Redirect back with message if not AJAX
                session_start();
                $_SESSION['cart_message'] = $result['message'];
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit;
            }
        }
    }
}
?>
