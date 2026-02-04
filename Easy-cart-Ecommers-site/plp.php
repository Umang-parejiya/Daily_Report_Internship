<?php
// Product Listing Page - plp.php
require_once 'config/session.php';
$current_page = 'products';
$page_title = 'Easy-Cart - All Products';

// Load products data
require_once 'data/products.php';

// Get filter parameters from URL
$filter_category = isset($_GET['category']) ? $_GET['category'] : null;
$filter_brand = isset($_GET['brand']) ? $_GET['brand'] : null;

// Filter products based on URL parameters
$filtered_products = $products;

if ($filter_category) {
    $filtered_products = array_filter($filtered_products, function($product) use ($filter_category) {
        return $product['category'] === $filter_category;
    });
    $page_title = 'Easy-Cart - ' . htmlspecialchars($filter_category);
}

if ($filter_brand) {
    $filtered_products = array_filter($filtered_products, function($product) use ($filter_brand) {
        return $product['brand'] === $filter_brand;
    });
    $page_title = 'Easy-Cart - ' . htmlspecialchars($filter_brand) . ' Products';
}

// Pagination settings
$items_per_page = 6;
$current_page_num = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

// Calculate pagination items
$total_items = count($filtered_products);
$total_pages = ceil($total_items / $items_per_page);

// Ensure current page is not beyond total pages
if ($total_pages > 0 && $current_page_num > $total_pages) {
    $current_page_num = $total_pages;
}

$offset = ($current_page_num - 1) * $items_per_page;
$paged_products = array_slice($filtered_products, $offset, $items_per_page);

// Include view
include 'views/plp.view.php';
?>
