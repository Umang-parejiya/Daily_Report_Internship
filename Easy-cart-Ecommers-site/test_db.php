<?php
/**
 * Database Connection Test File
 * This file tests the PostgreSQL connection and displays connection status
 */

echo "<h1>Database Connection Test</h1>";
echo "<hr>";

// Database Configuration
$host = 'localhost';
$port = '5432';
$dbname = 'easycart_db';
$user = 'postgres';
$password = '1234567';

echo "<h2>Connection Details:</h2>";
echo "<ul>";
echo "<li><strong>Host:</strong> $host</li>";
echo "<li><strong>Port:</strong> $port</li>";
echo "<li><strong>Database:</strong> $dbname</li>";
echo "<li><strong>User:</strong> $user</li>";
echo "</ul>";

echo "<h2>Testing Connection...</h2>";

try {
    // Build DSN (Data Source Name)
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    
    // Create PDO instance
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    echo "<div style='padding: 15px; background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; border-radius: 5px;'>";
    echo "<strong>✓ SUCCESS!</strong> Connected to PostgreSQL database successfully.";
    echo "</div>";
    
    // Test query to get PostgreSQL version
    echo "<h2>Database Information:</h2>";
    $stmt = $pdo->query('SELECT version()');
    $version = $stmt->fetch();
    echo "<p><strong>PostgreSQL Version:</strong><br>" . htmlspecialchars($version['version']) . "</p>";
    
    // Check if tables exist
    echo "<h2>Checking Tables:</h2>";
    $stmt = $pdo->query("
        SELECT table_name 
        FROM information_schema.tables 
        WHERE table_schema = 'public' 
        ORDER BY table_name
    ");
    $tables = $stmt->fetchAll();
    
    if (count($tables) > 0) {
        echo "<p><strong>Found " . count($tables) . " table(s):</strong></p>";
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>" . htmlspecialchars($table['table_name']) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<div style='padding: 15px; background-color: #fff3cd; border: 1px solid #ffeaa7; color: #856404; border-radius: 5px;'>";
        echo "<strong>⚠ WARNING:</strong> No tables found in the database. Please run the schema.sql file.";
        echo "</div>";
    }
    
    // Test product count if table exists
    $tableExists = false;
    foreach ($tables as $table) {
        if ($table['table_name'] === 'catalog_product_entity') {
            $tableExists = true;
            break;
        }
    }
    
    if ($tableExists) {
        echo "<h2>Product Data Test:</h2>";
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM catalog_product_entity");
        $result = $stmt->fetch();
        echo "<p><strong>Total Products:</strong> " . $result['count'] . "</p>";
        
        if ($result['count'] > 0) {
            echo "<p><strong>Sample Product:</strong></p>";
            $stmt = $pdo->query("SELECT entity_id, sku, name, price FROM catalog_product_entity LIMIT 1");
            $product = $stmt->fetch();
            echo "<ul>";
            echo "<li><strong>ID:</strong> " . $product['entity_id'] . "</li>";
            echo "<li><strong>SKU:</strong> " . $product['sku'] . "</li>";
            echo "<li><strong>Name:</strong> " . htmlspecialchars($product['name']) . "</li>";
            echo "<li><strong>Price:</strong> ₹" . number_format($product['price'], 2) . "</li>";
            echo "</ul>";
        }
    }
    
} catch (PDOException $e) {
    echo "<div style='padding: 15px; background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; border-radius: 5px;'>";
    echo "<strong>✗ ERROR!</strong> Connection failed.<br>";
    echo "<strong>Error Message:</strong> " . htmlspecialchars($e->getMessage());
    echo "</div>";
    
    echo "<h2>Troubleshooting Tips:</h2>";
    echo "<ul>";
    echo "<li>Make sure PostgreSQL is running</li>";
    echo "<li>Verify the database name 'easycart_db' exists</li>";
    echo "<li>Check if the username and password are correct</li>";
    echo "<li>Ensure PostgreSQL is listening on port 5432</li>";
    echo "<li>Check if pdo_pgsql extension is enabled in php.ini</li>";
    echo "</ul>";
}

echo "<hr>";
echo "<p><em>Test completed at: " . date('Y-m-d H:i:s') . "</em></p>";
?>
