<?php
// My Orders Page - orders.php
session_start();
$current_page = 'orders';
$page_title = 'Easy-Cart - My Orders';

// Load static data
require_once 'data/products.php';

// --- HANDLE ACTIONS ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_order'])) {
    $cancel_id = intval($_POST['order_id']);
    
    // Find and update status in session
    if (isset($_SESSION['orders'])) {
        foreach ($_SESSION['orders'] as &$order) {
            if ($order['order_id'] === $cancel_id && $order['status'] === 'Processing') {
                $order['status'] = 'Cancelled';
                break;
            }
        }
    }
    
    // Redirect to prevent resubmission
    header('Location: orders.php');
    exit;
}

// --- DATA SOURCE ---
// Use Session Orders if available, otherwise fallback to empty or static example
$my_orders = isset($_SESSION['orders']) ? $_SESSION['orders'] : [];

// Include view
include 'views/orders.view.php';
?>
