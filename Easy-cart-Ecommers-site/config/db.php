<?php
// Database Configuration
$host = 'localhost';      // Hostname (usually localhost)
$port = '5432';           // PostgreSQL Port (default is 5432)
$dbname = 'easycart_db';  // Database Name
$user = 'postgres';       // Database Username (default is often postgres)
$password = '1234567';       // Database Password (CHANGE THIS)

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
