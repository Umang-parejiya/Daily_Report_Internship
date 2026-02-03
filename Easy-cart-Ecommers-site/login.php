<?php
session_start();

// Handle AJAX Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_login'])) {
    header('Content-Type: application/json');
    
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Mock Validation: Accept any valid email with a non-empty password
    if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($password)) {
        $_SESSION['user_id'] = 1; // Mock user ID
        $_SESSION['user_email'] = $email;
        $_SESSION['logged_in'] = true;
        
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
    }
    exit;
}

// Login Page - login.php
$current_page = 'login';
$page_title = 'Easy-Cart - Login';

// Include view
include 'views/login.view.php';
?>
