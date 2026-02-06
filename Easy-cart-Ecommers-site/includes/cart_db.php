<?php
// includes/cart_db.php
require_once __DIR__ . '/../config/db.php';

/**
 * Merges Guest Cart into User Cart upon login.
 * 
 * @param string $guest_user_id
 * @param int $user_id
 * @return void
 */
function merge_guest_cart_to_user($guest_user_id, $user_id) {
    global $pdo;

    // 0. Handle Session Cart (if exists)
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        // Find or create guest cart in database
        $stmtGuestCart = $pdo->prepare("SELECT cart_id FROM sales_cart WHERE guest_user_id = ? ORDER BY created_at DESC LIMIT 1");
        $stmtGuestCart->execute([$guest_user_id]);
        $guestCartId = $stmtGuestCart->fetchColumn();
        
        if (!$guestCartId) {
            // Create guest cart if it doesn't exist
            $stmtNewCart = $pdo->prepare("INSERT INTO sales_cart (user_id, guest_user_id) VALUES (NULL, ?)");
            $stmtNewCart->execute([$guest_user_id]);
            $guestCartId = $pdo->lastInsertId();
        }
        
        // Migrate session cart items to database
        foreach ($_SESSION['cart'] as $productId => $qty) {
            // Fetch product details
            $stmtProduct = $pdo->prepare("SELECT * FROM catalog_product_entity WHERE entity_id = ?");
            $stmtProduct->execute([$productId]);
            $product = $stmtProduct->fetch();
            
            if ($product) {
                // Check if item already exists in database cart
                $stmtCheck = $pdo->prepare("SELECT item_id, quantity FROM sales_cart_items WHERE cart_id = ? AND product_id = ? AND status = 'active'");
                $stmtCheck->execute([$guestCartId, $productId]);
                $existing = $stmtCheck->fetch();
                
                if ($existing) {
                    // Update quantity
                    $newQty = $existing['quantity'] + $qty;
                    $newSubtotal = $product['price'] * $newQty;
                    $pdo->prepare("UPDATE sales_cart_items SET quantity = ?, subtotal = ? WHERE item_id = ?")->execute([$newQty, $newSubtotal, $existing['item_id']]);
                } else {
                    // Insert new item
                    $subtotal = $product['price'] * $qty;
                    $stmtInsert = $pdo->prepare("
                        INSERT INTO sales_cart_items (
                            cart_id, product_id, product_name, price, quantity, subtotal, status
                        ) VALUES (?, ?, ?, ?, ?, ?, 'active')
                    ");
                    $stmtInsert->execute([$guestCartId, $productId, $product['name'], $product['price'], $qty, $subtotal]);
                }
            }
        }
        
        // Clear session cart after migration
        unset($_SESSION['cart']);
    }

    // 1. Find Guest Cart
    $stmtGuest = $pdo->prepare("SELECT cart_id FROM sales_cart WHERE guest_user_id = ? ORDER BY created_at DESC LIMIT 1");
    $stmtGuest->execute([$guest_user_id]);
    $guestCartId = $stmtGuest->fetchColumn();

    if (!$guestCartId) return;

    // 2. Find or Create User Cart
    $stmtUser = $pdo->prepare("SELECT cart_id FROM sales_cart WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
    $stmtUser->execute([$user_id]);
    $userCartId = $stmtUser->fetchColumn();

    if (!$userCartId) {
        // Simple: Link the guest cart to the user
        $stmtUpdate = $pdo->prepare("UPDATE sales_cart SET user_id = ?, guest_user_id = NULL WHERE cart_id = ?");
        $stmtUpdate->execute([$user_id, $guestCartId]);
    } else {
        // Complex: Move items from guest cart to user cart
        $stmtItems = $pdo->prepare("SELECT * FROM sales_cart_items WHERE cart_id = ?");
        $stmtItems->execute([$guestCartId]);
        $guestItems = $stmtItems->fetchAll();

        foreach ($guestItems as $item) {
            if ($item['status'] === 'active') {
                // Check if an active item for this product already exists in user cart
                $stmtCheck = $pdo->prepare("SELECT item_id, quantity FROM sales_cart_items WHERE cart_id = ? AND product_id = ? AND status = 'active'");
                $stmtCheck->execute([$userCartId, $item['product_id']]);
                $existing = $stmtCheck->fetch();

                if ($existing) {
                    $newQty = $existing['quantity'] + $item['quantity'];
                    $newSubtotal = $item['price'] * $newQty;
                    $pdo->prepare("UPDATE sales_cart_items SET quantity = ?, subtotal = ? WHERE item_id = ?")->execute([$newQty, $newSubtotal, $existing['item_id']]);
                    // Delete the guest item as it's merged
                    $pdo->prepare("DELETE FROM sales_cart_items WHERE item_id = ?")->execute([$item['item_id']]);
                } else {
                    // Just reassign the guest item to user cart
                    $pdo->prepare("UPDATE sales_cart_items SET cart_id = ? WHERE item_id = ?")->execute([$userCartId, $item['item_id']]);
                }
            } else {
                // For inactive items, just reassign cart_id to preserve history
                $pdo->prepare("UPDATE sales_cart_items SET cart_id = ? WHERE item_id = ?")->execute([$userCartId, $item['item_id']]);
            }
        }

        // Delete guest cart entry
        $pdo->prepare("DELETE FROM sales_cart WHERE cart_id = ?")->execute([$guestCartId]);
    }
}
?>
