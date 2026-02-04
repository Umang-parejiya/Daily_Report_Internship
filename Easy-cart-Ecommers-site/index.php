<?php
// Home Page - index.php
require_once 'config/session.php';
$current_page = 'home';
$page_title = 'Easy-Cart - Home';

// Load data files
// Load Database Configuration
require_once 'config/db.php';

// 1. Fetch Featured Products (First 4)
$stmt = $pdo->prepare("
    SELECT 
        p.entity_id as id, 
        p.name, 
        p.price, 
        p.image, 
        p.brand, 
        p.shipping_type,
        c.name as category
    FROM catalog_product_entity p
    LEFT JOIN catalog_category_product ccp ON p.entity_id = ccp.product_id
    LEFT JOIN catalog_category_entity c ON ccp.category_id = c.entity_id
    ORDER BY p.entity_id ASC
    LIMIT 4
");
$stmt->execute();
$featured_products = $stmt->fetchAll();

// 2. Fetch Categories
$stmt_cat = $pdo->query("SELECT entity_id as id, name FROM catalog_category_entity LIMIT 6");
$categories = $stmt_cat->fetchAll();

// 3. Fetch Brands
$stmt_brand = $pdo->query("SELECT name, logo_url as logo FROM brands ORDER BY id ASC LIMIT 8");
$brands = $stmt_brand->fetchAll();

// Include view
include 'views/index.view.php';


?>
