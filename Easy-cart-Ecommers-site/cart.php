<?php
session_start();
$current_page = 'cart';
$page_title = 'Easy-Cart - My Cart';

require_once 'data/products.php';

// --- HANDLE CART ACTIONS ---

// 1. Remove Item
if (isset($_GET['remove'])) {
    $remove_id = intval($_GET['remove']);
    // Remove all instances of this ID
    if (isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array_filter($_SESSION['cart'], function($id) use ($remove_id) {
            return $id != $remove_id;
        });
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index
    }

    // AJAX Response
    if (isset($_GET['ajax_remove'])) {
        header('Content-Type: application/json');

        // Recalculate totals
        $cart_counts = array_count_values($_SESSION['cart']);
        
        // Calculate subtotal
        $subtotal = 0;
        foreach ($cart_counts as $id => $qty) {
            foreach ($products as $p) {
                if ($p['id'] === $id) {
                    $subtotal += $p['price'] * $qty;
                }
            }
        }
        
        // Calculate total quantity and discount
        $total_quantity = array_sum($cart_counts);
        $discount = 0;
        $discount_percentage = 0;
        if ($total_quantity > 0 && $total_quantity % 2 === 0) {
            $discount_percentage = min($total_quantity, 50);
            $discount = ($subtotal * $discount_percentage) / 100;
        }
        
        // [Phase 5] Determine Cart Type
        // Rule: If ANY item is 'freight' OR Subtotal > 300 => Cart is Freight
        $has_freight = false;
        foreach ($cart_counts as $id => $qty) {
            foreach ($products as $p) {
                if ($p['id'] === $id) {
                    if (isset($p['shipping_type']) && $p['shipping_type'] === 'freight') {
                        $has_freight = true;
                    }
                    break;
                }
            }
        }
        
        $cart_type = 'express'; // Default
        if ($has_freight) {
            $cart_type = 'freight';
        } elseif ($subtotal >= 300) {
            $cart_type = 'freight'; // Price threshold trigger
        }
        $_SESSION['cart_type'] = $cart_type;

        $total = $subtotal - $discount;
        
        echo json_encode([
            'success' => true,
            'cartCount' => count($_SESSION['cart']),
            'newSubtotal' => number_format($subtotal),
            'newDiscount' => number_format($discount),
            'discountPercentage' => $discount_percentage,
            'hasDiscount' => ($discount > 0),
            'newTotal' => number_format($total),
            'cartType' => ucfirst($cart_type)
        ]);
        exit;
    }

    header('Location: cart.php');
    exit;
}

// 2. Update Quantity (Add/Subtract) - JSON/AJAX Support
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['product_id'])) {
    $action = $_POST['action'];
    $pid = intval($_POST['product_id']);
    
    // Perform modification
    if ($action === 'increase') {
        $_SESSION['cart'][] = $pid;
    } elseif ($action === 'decrease') {
        if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $key = array_search($pid, $_SESSION['cart']);
        if ($key !== false) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); 
        }
    }

    // If AJAX request, return new state
    if (isset($_POST['ajax_update'])) {
        header('Content-Type: application/json');
        
        // Recalculate totals
        $cart_counts = array_count_values($_SESSION['cart']);
        $new_qty = isset($cart_counts[$pid]) ? $cart_counts[$pid] : 0;
        
        // Calculate subtotal
        $subtotal = 0;
        foreach ($cart_counts as $id => $qty) {
            foreach ($products as $p) {
                if ($p['id'] === $id) {
                    $subtotal += $p['price'] * $qty;
                }
            }
        }
        
        // Calculate total quantity and discount
        $total_quantity = array_sum($cart_counts);
        $discount = 0;
        $discount_percentage = 0;
        if ($total_quantity > 0 && $total_quantity % 2 === 0) {
            $discount_percentage = min($total_quantity, 50);   // 50% max discount
            $discount = ($subtotal * $discount_percentage) / 100;
        }
        
        // [Phase 5] Determine Cart Type (AJAX Update)
        // Re-evaluate rules on quantity change (Subtotal change might flip Express -> Freight)
        $has_freight = false;
        foreach ($cart_counts as $id => $qty) {
            foreach ($products as $p) {
                if ($p['id'] === $id) {
                     if (isset($p['shipping_type']) && $p['shipping_type'] === 'freight') {
                        $has_freight = true;
                    }
                    break;
                }
            }
        }

        $cart_type = 'express';
        if ($has_freight) {
            $cart_type = 'freight';
        } elseif ($subtotal >= 300) {
            $cart_type = 'freight';
        }
        $_SESSION['cart_type'] = $cart_type;
        
        $total = $subtotal - $discount;
        
        echo json_encode([
            'success' => true,
            'productId' => $pid,
            'newQty' => $new_qty,
            'newSubtotal' => number_format($subtotal),
            'newDiscount' => number_format($discount),
            'discountPercentage' => $discount_percentage,
            'hasDiscount' => ($discount > 0),
            'newTotal' => number_format($total),
            'cartType' => ucfirst($cart_type),
            'removed' => ($new_qty === 0)
        ]);
        exit;
    }

    // Fallback for non-AJAX
    header('Location: cart.php');
    exit;
}

// --- PREPARE DATA FOR DISPLAY ---

// Initialize cart array if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Count quantities: [1 => 2, 4 => 1] (Product ID => Qty)
$cart_counts = array_count_values($_SESSION['cart']);

// Build rich item list
$cart_items = [];
$subtotal = 0;

foreach ($cart_counts as $product_id => $quantity) {
    // Find product details
    foreach ($products as $p) {
        if ($p['id'] === $product_id) {
            $line_total = $p['price'] * $quantity;
            $subtotal += $line_total;
            
            $cart_items[] = [
                'id' => $p['id'],
                'name' => $p['name'],
                'price' => $p['price'],
                'image' => $p['image'],
                'brand' => $p['brand'],
                'qty' => $quantity,
                'total' => $line_total
            ];
            break; 
        }
    }
}

// Calculate total quantity discount even logic 
$total_quantity = array_sum($cart_counts);

// Calculate discount if quantity is even
$discount = 0;
$discount_percentage = 0;
if ($total_quantity > 0 && $total_quantity % 2 === 0) {
    $discount_percentage = min($total_quantity, 50);
    $discount = ($subtotal * $discount_percentage) / 100;
}

// [Phase 5] Determine Cart Type (Page Load)
$has_freight = false;
foreach ($cart_items as $item) {
    foreach($products as $p) {
        if ($p['id'] === $item['id']) {
            if (isset($p['shipping_type']) && $p['shipping_type'] === 'freight') {
                $has_freight = true;
            }
            break;
        }
    }
}

$cart_type = 'express';
if ($has_freight) {
    $cart_type = 'freight';
} elseif ($subtotal >= 300) {
    $cart_type = 'freight';
}
$_SESSION['cart_type'] = $cart_type;

// Total (shipping calculated at checkout)
$total = $subtotal - $discount;

// Include view
include 'views/cart.view.php';
?>
