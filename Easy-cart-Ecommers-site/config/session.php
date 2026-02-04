<?php
// config/session.php
// Handles session start and Guest ID generation

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generate unique Guest ID if user is not logged in
if (!isset($_SESSION['user_id'])) {
    if (!isset($_SESSION['guest_user_id'])) {
        // Generate a random unique ID
        $_SESSION['guest_user_id'] = 'guest_' . bin2hex(random_bytes(8));
    }
}
