<?php
require_once 'config/db.php';

$tables = ['sales_cart_items', 'cart_items'];

foreach ($tables as $table) {
    try {
        $pdo->exec("ALTER TABLE $table ADD COLUMN IF NOT EXISTS status VARCHAR(20) DEFAULT 'active'");
        echo "Successfully added/verified 'status' column in $table\n";
    } catch (PDOException $e) {
        if ($e->getCode() == '42P01') {
            echo "Table $table does not exist, skipping.\n";
        } else {
            echo "Error updating $table: " . $e->getMessage() . "\n";
        }
    }
}
?>
