<?php
// Home Page - index.php
$current_page = 'home';
$page_title = 'Easy-Cart - Home';

// Load data files
require_once 'data/products.php';
require_once 'data/categories.php';
require_once 'data/brands.php';

// Get featured products (first 4 products)
$featured_products = array_slice($products, 0, 4);

// Include view
include 'views/index.view.php';


?>
