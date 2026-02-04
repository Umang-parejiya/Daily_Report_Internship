<?php
require_once 'config/db.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_login'])) {
    header('Content-Type: application/json');

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Email and password are required']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT id, first_name, password_hash FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        // Merge Logic
        require_once 'includes/cart_db.php';
        
        $guest_session_key = isset($_SESSION['guest_user_id']) ? $_SESSION['guest_user_id'] : null;
        if ($guest_session_key) {
            merge_guest_cart_to_user($guest_session_key, $user['id']);
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['first_name'];
        unset($_SESSION['guest_user_id']);
        
        // Update old mock session keys just in case
        $_SESSION['logged_in'] = true;
        $_SESSION['user_email'] = $email;

        echo json_encode(['success' => true, 'message' => 'Login successful']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
    }
    exit;
}

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$page_title = 'Login';
include 'views/login.view.php';
?>
