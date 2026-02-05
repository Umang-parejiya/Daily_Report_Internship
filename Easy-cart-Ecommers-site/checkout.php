<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
/**
 * checkout.php
 * Overhauled to use official sales_order tables and soft-deactivate cart items.
 */
require_once 'config/session.php';
require_once 'config/db.php';
require_once 'config/auth.php'; // Enforce login

$userId = $_SESSION['user_id'];
$current_page = 'cart';
$page_title = 'Easy-Cart - Checkout';

// 0. Fetch Customer Info
$stmtCust = $pdo->prepare("SELECT first_name, last_name, email FROM users WHERE id = ?");
$stmtCust->execute([$userId]);
$user = $stmtCust->fetch();

// 1. Find User Cart ID
$stmtCart = $pdo->prepare("SELECT cart_id FROM sales_cart WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
$stmtCart->execute([$userId]);
$cartId = $stmtCart->fetchColumn();

// If no cart, redirect back to cart page
if (!$cartId) {
    header('Location: cart.php');
    exit;
}

// 2. Fetch Active Cart Totals
$stmtTotals = $pdo->prepare("SELECT SUM(quantity) as count, SUM(subtotal) as subtotal FROM sales_cart_items WHERE cart_id = ? AND status = 'active'");
$stmtTotals->execute([$cartId]);
$totals = $stmtTotals->fetch();

$subtotal = $totals['subtotal'] ?? 0;
$total_quantity = $totals['count'] ?? 0;

if ($total_quantity == 0) {
    header('Location: cart.php');
    exit;
}

// Handle Shipping Update (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_update_shipping'])) {
    header('Content-Type: application/json');
    
    // Save Selection to Session
    $received_id = $_POST['selectedMethod'] ?? $_POST['shipping_method'] ?? null;
    $id_to_key_map = ['1' => 'standard', '2' => 'express', '3' => 'white_glove', '4' => 'freight'];
    $method = $id_to_key_map[$received_id] ?? null;
    
    if ($method) {
        $_SESSION['shipping_method'] = $method;
    }

    // Calculate Discount
    $discount = 0;
    if ($total_quantity > 0 && $total_quantity % 2 === 0) {
        $discount_percentage = min($total_quantity, 50);
        $discount = ($subtotal * $discount_percentage) / 100;
    }
    
    // Calculate Shipping Cost
    $cost = 0;
    switch($method) {
        case 'standard': $cost = 40; break;
        case 'express': $cost = min(80, $subtotal * 0.10); break;
        case 'white_glove': $cost = min(150, $subtotal * 0.05); break;
        case 'freight': $cost = min(200, $subtotal * 0.03); break;
    }
    
    $taxable = ($subtotal - $discount) + $cost;
    $tax = $taxable * 0.18;
    $total = $taxable + $tax;
    
    echo json_encode([
        'success' => true,
        'shipping_cost' => $cost,
        'tax' => $tax,
        'total' => $total,
        'formatted_shipping' => ($cost == 0) ? 'FREE' : '₹' . number_format($cost),
        'formatted_tax' => '₹' . number_format($tax),
        'formatted_total' => '₹' . number_format($total)
    ]);
    exit;
}

// Handle Order Creation (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_place_order'])) {
    // Start output buffering
    ob_start();
    header('Content-Type: application/json');
    
    try {
        $pdo->beginTransaction();

        // 1. Get Selected Method (Robust Resolution)
        $id_to_key_map = ['1' => 'standard', '2' => 'express', '3' => 'white_glove', '4' => 'freight'];
        $received_val = $_POST['selectedMethod'] ?? null;
        
        $method = 'standard'; // Default
        
        // Try to resolve from POST (ID or Name)
        if ($received_val) {
            if (isset($id_to_key_map[$received_val])) {
                $method = $id_to_key_map[$received_val];
            } elseif (in_array($received_val, $id_to_key_map)) {
                $method = $received_val;
            }
        } 
        // Fallback to Session if POST failed or wasn't sent
        elseif (isset($_SESSION['shipping_method'])) {
            $method = $_SESSION['shipping_method'];
        }

        // 2. Re-calculate everything to be sure
        
        // [FIX] Always calculate subtotal from database
        $stmtFresh = $pdo->prepare("SELECT SUM(subtotal) FROM sales_cart_items WHERE cart_id = ? AND status = 'active'");
        $stmtFresh->execute([$cartId]);
        $db_subtotal = $stmtFresh->fetchColumn();

        // Validate Subtotal
        $subtotal = floatval($db_subtotal); // Ensure it's a number (0 if null)
        
        if ($subtotal <= 0) {
            throw new Exception("Cart appears to be empty (Subtotal is 0 or NULL).");
        }

        $discount = 0;
        if ($total_quantity > 0 && $total_quantity % 2 === 0) {
            $discount_percentage = min($total_quantity, 50);
            $discount = ($subtotal * $discount_percentage) / 100;
        }

        $shipping_cost = isset($_POST['shipping_cost']) ? floatval($_POST['shipping_cost']) : 0;
        $order_subtotal = $subtotal; 
        $taxable = ($order_subtotal - $discount);
        $tax = ($taxable + $shipping_cost) * 0.18;
        $final_total = $taxable + $tax + $shipping_cost;

        // Generate a friendly increment ID
        $increment_id = '1000' . mt_rand(10, 99) . mt_rand(100, 999);

        // 2.1 Read POST Data (Task: Ensure PHP receives address values)
        $payment_method = $_POST['payment_method'] ?? 'card';
        // Debugging: Log the received POST data to PHP error log
        error_log("Checkout POST Data: " . print_r($_POST, true));
        
        // Priority: POST data -> Fallback: Database session user (for email/name) -> Default: empty
        $lastname   = trim($_POST['lastname'] ?? $user['last_name'] ?? '');
        $firstname  = trim($_POST['firstname'] ?? $user['first_name'] ?? '');
        $email      = trim($_POST['email'] ?? $user['email'] ?? '');
        $street     = trim($_POST['street'] ?? '');
        $city       = trim($_POST['city'] ?? '');
        $region     = trim($_POST['region'] ?? '');
        $postcode   = trim($_POST['postcode'] ?? '');
        $telephone  = trim($_POST['telephone'] ?? '');

        // 2.2 Insert Address (sales_cart_address)
        // User requested named parameters
        $sqlAddress = "INSERT INTO sales_cart_address
        (cart_id, address_type, firstname, lastname, email, street, city, region, postcode, telephone)
        VALUES
        (:cart_id, :address_type, :firstname, :lastname, :email, :street, :city, :region, :postcode, :telephone)";
        
        $stmtAddress = $pdo->prepare($sqlAddress);
        $stmtAddress->execute([
            ':cart_id'      => $cartId,
            ':address_type' => 'shipping',
            ':firstname'    => $firstname,
            ':lastname'     => $lastname,
            ':email'        => $email,
            ':street'       => $street,
            ':city'         => $city,
            ':region'       => $region,
            ':postcode'     => $postcode,
            ':telephone'    => $telephone
        ]);

        // Updated variables for sales_order to use captured values
        $fname = $firstname;
        $lname = $lastname;
        $phone = $telephone;

        // 2.3 Insert Shipping Method...
        $stmtShippingInsert = $pdo->prepare("
            INSERT INTO sales_cart_shipping (
                cart_id, method_code, amount
            ) VALUES (?, ?, ?)
        ");
        $stmtShippingInsert->execute([$cartId, $method, $shipping_cost]);

        // 2.4 Insert Payment Method (sales_cart_payment)
        $stmtPaymentInsert = $pdo->prepare("
            INSERT INTO sales_cart_payment (
                cart_id, method_code
            ) VALUES (?, ?)
        ");
        $stmtPaymentInsert->execute([$cartId, $payment_method]);

        // 3. Create Order in sales_order (Dynamic Data)
        $stmtOrder = $pdo->prepare("
            INSERT INTO sales_order (
                increment_id, status, subtotal, shipping_amount, tax_amount, grand_total,
                customer_email, customer_firstname, customer_lastname, shipping_method, payment_method, 
                created_at, updated_at
            ) VALUES (?, 'processing', ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW()) RETURNING entity_id
        ");
        $stmtOrder->execute([
            $increment_id, 
            $order_subtotal, 
            $shipping_cost, 
            $tax, 
            $final_total,
            $email, 
            $fname, 
            $lname, 
            ucfirst($method),
            ucfirst($payment_method)
        ]);
        $order_id = $stmtOrder->fetchColumn();

        // 4. Move Active Items to sales_order_item
        $stmtActiveItems = $pdo->prepare("
            SELECT ci.*, p.sku 
            FROM sales_cart_items ci 
            JOIN catalog_product_entity p ON ci.product_id = p.entity_id 
            WHERE ci.cart_id = ? AND ci.status = 'active'
        ");
        $stmtActiveItems->execute([$cartId]);
        $activeItems = $stmtActiveItems->fetchAll();

        $stmtInsertItem = $pdo->prepare("
            INSERT INTO sales_order_item (
                order_id, product_id, sku, name, price, quantity, row_total
            ) VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        foreach ($activeItems as $item) {
            $stmtInsertItem->execute([
                $order_id,
                $item['product_id'],
                $item['sku'],
                $item['product_name'],
                $item['price'],
                $item['quantity'],
                $item['subtotal']
            ]);
        }

        // 5. Update Cart Items to Inactive
        $pdo->prepare("UPDATE sales_cart_items SET status = 'inactive' WHERE cart_id = ? AND status = 'active'")->execute([$cartId]);
        
        // 6. Clear Session
        unset($_SESSION['shipping_method']);
        unset($_SESSION['cart_type']);

        $pdo->commit();
        ob_end_clean();
        echo json_encode(['success' => true, 'order_id' => $order_id, 'redirect' => 'orders']);
    } catch (Exception $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'Order failed: ' . $e->getMessage()]);
    }
    exit;
}

// Prepare Data for Display
$stmtItems = $pdo->prepare("SELECT ci.*, p.image FROM sales_cart_items ci JOIN catalog_product_entity p ON ci.product_id = p.entity_id WHERE ci.cart_id = ? AND ci.status = 'active'");
$stmtItems->execute([$cartId]);
$db_items = $stmtItems->fetchAll();

$cart_items = [];
foreach ($db_items as $item) {
    $cart_items[] = [
        'product' => ['id' => $item['product_id'], 'name' => $item['product_name'], 'price' => $item['price'], 'image' => $item['image_path'] ?: $item['image']],
        'quantity' => $item['quantity'],
        'subtotal' => $item['subtotal']
    ];
}

// Shipping options logic preserved
$shipping_options = [
    'standard' => ['id' => 1, 'name' => 'Standard Shipping', 'cost' => 40, 'delivery' => 'Flat ₹40 ', 'icon' => '🚚'],
    'express' => ['id' => 2, 'name' => 'Express Shipping', 'cost' => min(80, $subtotal * 0.10), 'delivery' => 'Flat ₹80 OR 10% of subtotal ', 'icon' => '⚡'],
    'white_glove' => ['id' => 3, 'name' => 'White Glove Delivery', 'cost' => min(150, $subtotal * 0.05), 'delivery' => 'Flat ₹150 OR 5% of subtotal ', 'icon' => '🧤'],
    'freight' => ['id' => 4, 'name' => 'Freight Shipping', 'cost' => min(200, $subtotal * 0.03), 'delivery' => '3% of subtotal OR Min $200', 'icon' => '🚢']
];

$selected_shipping = $_SESSION['shipping_method'] ?? null;
$cart_type = $_SESSION['cart_type'] ?? 'express';
$valid_methods = ($cart_type === 'freight') ? ['white_glove', 'freight'] : ['standard', 'express'];

if (!$selected_shipping || !in_array($selected_shipping, $valid_methods)) {
    $selected_shipping = ($cart_type === 'freight') ? 'freight' : 'standard';
    $_SESSION['shipping_method'] = $selected_shipping;
}

$shipping = $shipping_options[$selected_shipping]['cost'];
$discount = 0;
$discount_percentage = 0;
if ($total_quantity > 0 && $total_quantity % 2 === 0) {
    $discount_percentage = min($total_quantity, 50);
    $discount = ($subtotal * $discount_percentage) / 100;
}
$tax = (($subtotal - $discount) + $shipping) * 0.18;
$total = ($subtotal - $discount) + $shipping + $tax;

include 'views/checkout.view.php';
?>
