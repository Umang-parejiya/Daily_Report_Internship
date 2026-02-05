<?php
/**
 * Master Setup Script
 * Executes all SQL files to initialize the database structure and data.
 */

// Load Database Configuration
require_once 'config/db.php';

// List of SQL files to execute in order
$sql_files = [
    'database/schema.sql', // Main Schema (Products, Categories, Cart, Orders)
    'database/users.sql',  // Users Table
    'database/brands.sql'  // Brands Table
];

echo "<h1>Database Setup</h1>";
echo "<hr>";

foreach ($sql_files as $file) {
    echo "<h3>Processing: " . htmlspecialchars($file) . "</h3>";
    
    if (file_exists($file)) {
        try {
            $sql_content = file_get_contents($file);
            $pdo->exec($sql_content);
            echo "<p style='color: green;'><strong>✓ Success:</strong> Executed successfully.</p>";
        } catch (PDOException $e) {
            echo "<p style='color: red;'><strong>✗ Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    } else {
        echo "<p style='color: orange;'><strong>⚠ Warning:</strong> File not found.</p>";
    }
    echo "<hr>";
}

// Additional Schema Updates (from migrate_status.php)
echo "<h3>Processing: Schema Updates</h3>";
$tables = ['sales_cart_items', 'cart_items'];
foreach ($tables as $table) {
    try {
        $pdo->exec("ALTER TABLE $table ADD COLUMN IF NOT EXISTS status VARCHAR(20) DEFAULT 'active'");
        echo "<p style='color: green;'><strong>✓ Success:</strong> Added/Verified 'status' column in $table.</p>";
    } catch (PDOException $e) {
        if ($e->getCode() == '42P01') {
             echo "<p style='color: orange;'><strong>⚠ Warning:</strong> Table $table does not exist, skipping.</p>";
        } else {
             echo "<p style='color: red;'><strong>✗ Error:</strong> Error updating $table: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
}
echo "<hr>";

echo "<p>Setup completed.</p>";
echo "<a href='index.php'>Go to Home Page</a>";
?>
