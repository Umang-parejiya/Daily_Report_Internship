<?php
// Product Detail Page - pdp.php
session_start(); // Start session for cart functionality

$current_page = 'products';
$page_title = 'Easy-Cart - Product Details';

// Load products data
require_once 'data/products.php';

// Get product ID from URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Find the product
$product = null;
foreach ($products as $p) {
    if ($p['id'] === $product_id) {
        $product = $p;
        break;
    }
}

// Handle AJAX Add to Cart phase 5
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_add'])) {
    header('Content-Type: application/json');
    
    // Initialize cart
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    $pid = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    
    if ($pid > 0) {
        $_SESSION['cart'][] = $pid;
        echo json_encode([
            'success' => true,
            'cartCount' => count($_SESSION['cart']),
            'message' => 'Product added to cart successfully!'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid product ID'
        ]);
    }
    exit;
}

// Handle Add to Cart POST request
$cart_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    // Initialize cart if not exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Add product ID to cart
    if ($product) {
        $_SESSION['cart'][] = $product_id;
        $cart_message = 'Product added to cart successfully!';
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
