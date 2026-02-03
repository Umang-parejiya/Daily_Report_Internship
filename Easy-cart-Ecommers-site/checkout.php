<?php
// Checkout Page - checkout.php
session_start();

$current_page = 'cart';
$page_title = 'Easy-Cart - Checkout';

// Load products data
require_once 'data/products.php';

// Handle Shipping Update (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_update_shipping'])) {
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        echo json_encode(['success' => false, 'message' => 'Cart is empty']);
        exit;
    }

    // Save Selection to Session
    $received_id = $_POST['selectedMethod'] ?? $_POST['shipping_method'] ?? null;
    
    // Map numerical IDs back to method keys
    $id_to_key_map = [
        '1' => 'standard',
        '2' => 'express',
        '3' => 'white_glove',
        '4' => 'freight'
    ];
    
    $method = $id_to_key_map[$received_id] ?? null;
    
    if ($method) {
        $_SESSION['shipping_method'] = $method;
    }

    // Calculate Subtotal
    $cart_product_ids = array_count_values($_SESSION['cart']);
    $subtotal = 0;
    $total_quantity = 0;
    
    foreach ($cart_product_ids as $pid => $qty) {
        foreach ($products as $p) {
            if ($p['id'] == $pid) {
                 $subtotal += $p['price'] * $qty;
                 $total_quantity += $qty;
                 break;
            }
        }
    }
    
    // Calculate Discount
    $discount = 0;
    if ($total_quantity > 0 && $total_quantity % 2 === 0) {
        $discount_percentage = min($total_quantity, 50);
        $discount = ($subtotal * $discount_percentage) / 100;
    }
    
    // Calculate Shipping Cost
    // We assume the method passed is valid or default to 0/Standard if strictly needed for calculation context?
    // Cost logic repeats mainly because $shipping_options isn't available here yet.
    // I'll replicate the switch.
    $cost = 0;
    switch($method) {
        case 'standard': 
            $cost = 40; 
            break;
        case 'express': 
            $cost = min(80, $subtotal * 0.10); 
            break;
        case 'white_glove': 
            $cost = min(150, $subtotal * 0.05); 
            break;
        case 'freight': 
            $cost = min(200, $subtotal * 0.03); 
            break;
        default:
            $cost = 0; // If invalid or null
    }
    
    // Calculate Tax (18% on Taxable Amount including Shipping)
    $taxable = ($subtotal - $discount) + $cost;
    $tax = $taxable * 0.18;
    
    // Calculate Total
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
    header('Content-Type: application/json');
    
    // Simulate Order ID
    $order_id = mt_rand(100000, 999999);
    
    // Calculate total again for security
    $cart_product_ids = array_count_values($_SESSION['cart']);
    $order_items = [];
    $order_total = 0;
    
    foreach ($cart_product_ids as $pid => $qty) {
        foreach ($products as $p) {
            if ($p['id'] === $pid) {
                $line_total = $p['price'] * $qty;
                $order_total += $line_total;
                $order_items[] = [
                    'product_id' => $pid,
                    'quantity' => $qty,
                    'price' => $p['price']
                ];
                break;
            }
        }
    }
    
    // Calculate total quantity for discount quantity
    $total_quantity = 0;
    foreach ($order_items as $item) {
        $total_quantity += $item['quantity'];
    }

    // Calculate discount if quantity is even
    $discount = 0;
    if ($total_quantity > 0 && $total_quantity % 2 === 0) {
        $discount_percentage = min($total_quantity, 50);
        $discount = ($order_total * $discount_percentage) / 100;
    }

    // Add Shipping (Simplified based on POST or default)
    $shipping_cost = isset($_POST['shipping_cost']) ? floatval($_POST['shipping_cost']) : 0;
    
    // Calculate Tax (18% on Subtotal - Discount)
    $taxable = ($order_total - $discount);
    $tax = $taxable * 0.18;
    
    // Final Total
    $final_total = $taxable + $tax + $shipping_cost;
    
    // Create Order Object
    $new_order = [
        'order_id' => $order_id,
        'date' => date('Y-m-d'),
        'status' => 'Processing',
        'total' => $final_total,
        'items' => $order_items
    ];
    
    // Save to Session (Mock Database)
    if (!isset($_SESSION['orders'])) {
        $_SESSION['orders'] = [];
    }
    // Prepend new order
    array_unshift($_SESSION['orders'], $new_order);
    
    // Clear Cart
    unset($_SESSION['cart']);
    
    echo json_encode([
        'success' => true,
        'order_id' => $order_id,
        'redirect' => 'orders.php'
    ]);
    exit;
}

// Check if cart is empty
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
    header('Location: cart.php');
    exit;
}

// Get cart items with quantities
$cart_items = [];
$cart_product_ids = array_count_values($_SESSION['cart']);

foreach ($cart_product_ids as $product_id => $quantity) {
    foreach ($products as $product) {
        if ($product['id'] === $product_id) {
            $cart_items[] = [
                'product' => array_merge($product, ['image' => $product['image']]),
                'quantity' => $quantity,
                'subtotal' => $product['price'] * $quantity
            ];
            break;
        }
    }
}

// Calculate subtotal
$subtotal = 0;
foreach ($cart_items as $item) {
    $subtotal += $item['subtotal'];
}

// Shipping options with dynamic cost calculation (Phase 4 Rules)
$shipping_options = [
    'standard' => [
        'id' => 1,
        'name' => 'Standard Shipping', 
        'cost' => 40,
        'delivery' => 'Flat ₹40 ',
        'icon' => '🚚'
    ],
    'express' => [
        'id' => 2,
        'name' => 'Express Shipping', 
        'cost' => min(80, $subtotal * 0.10),
        'delivery' => 'Flat ₹80 OR 10% of subtotal (whichever is lower) ',
        'icon' => '⚡'
    ],
    'white_glove' => [
        'id' => 3,
        'name' => 'White Glove Delivery', 
        'cost' => min(150, $subtotal * 0.05),
        'delivery' => 'Flat ₹150 OR 5% of subtotal (whichever is lower) ',
        'icon' => '🧤'
    ],
    'freight' => [
        'id' => 4,
        'name' => 'Freight Shipping', 
        'cost' => min(200, $subtotal * 0.03),
        'delivery' => '3% of subtotal OR Minimum $200',
        'icon' => '🚢'
    ]
];

// -------------------------------------------------------------------
// [Phase 5] Shipping Logic: Determine Valid Shipping Methods
// -------------------------------------------------------------------
// Get selected shipping method (from Session or null)
$selected_shipping = $_SESSION['shipping_method'] ?? null;
// Retrieve Cart Type determined in cart.php (defaults to 'express' if missing)
$cart_type = $_SESSION['cart_type'] ?? 'express';

// Define valid methods per type based on business rules:
// - Freight Cart: Only White Glove & Freight allowed
// - Express Cart: Only Standard & Express allowed
$valid_methods = ($cart_type === 'freight') ? ['white_glove', 'freight'] : ['standard', 'express'];

// Validate shipping method against valid list. If invalid (e.g. user refreshed or session persisted invalid type), reset to default.
if (!$selected_shipping || !in_array($selected_shipping, $valid_methods)) {
    // Auto-select default: Standard for Express, Freight for Freight
    $selected_shipping = ($cart_type === 'freight') ? 'freight' : 'standard';
    $_SESSION['shipping_method'] = $selected_shipping;
}

// Calculate shipping cost based on the VALID selected method
$shipping = $shipping_options[$selected_shipping]['cost'];

// Calculate total quantity for discount
$total_quantity = 0;
foreach ($cart_items as $item) {
    $total_quantity += $item['quantity'];
}

// Calculate discount if quantity is even
$discount = 0;
$discount_percentage = 0;
if ($total_quantity > 0 && $total_quantity % 2 === 0) {
    $discount_percentage = min($total_quantity, 50);   // max 50% discount
    $discount = ($subtotal * $discount_percentage) / 100;
}

// Calculate Tax (18% on Subtotal - Discount + Shipping)
$taxable_amount = ($subtotal - $discount) + $shipping;
$tax = $taxable_amount * 0.18;

// Calculate Final Total
$total = $taxable_amount + $tax;

// Include view
include 'views/checkout.view.php';
?>
