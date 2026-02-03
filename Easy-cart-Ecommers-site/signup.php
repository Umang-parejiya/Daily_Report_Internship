<?php
session_start();

// Handle AJAX Signup
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_signup'])) {
    header('Content-Type: application/json');
    
    $email = $_POST['email'] ?? '';
    // Mock Validation: simple success if email is valid
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['user_id'] = mt_rand(10, 100);
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name'] = $_POST['first_name'] ?? 'User';
        $_SESSION['logged_in'] = true;
        
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid email address provided']);
    }
    exit;
}

// Signup Page - signup.php
$current_page = 'login';
$page_title = 'Easy-Cart - Sign Up';

// Include view
include 'views/signup.view.php';
?>
