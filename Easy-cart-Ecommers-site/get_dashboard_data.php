<?php
require_once 'config/session.php';
require_once 'config/db.php';

header('Content-Type: application/json');

// 1. Auth Check
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // 2. Get User Email (Schema links orders by email)
    $stmtUser = $pdo->prepare("SELECT email FROM users WHERE id = ?");
    $stmtUser->execute([$user_id]);
    $user_email = $stmtUser->fetchColumn();

    if (!$user_email) {
        throw new Exception("User not found");
    }

    // 3. KPI Metrics
    
    // Total Orders
    $stmtOrders = $pdo->prepare("SELECT COUNT(*) FROM sales_order WHERE customer_email = ?");
    $stmtOrders->execute([$user_email]);
    $total_orders = $stmtOrders->fetchColumn();

    // Total Spent
    $stmtSpent = $pdo->prepare("SELECT COALESCE(SUM(grand_total), 0) FROM sales_order WHERE customer_email = ?");
    $stmtSpent->execute([$user_email]);
    $total_spent = $stmtSpent->fetchColumn();

    // 4. Chart Data (Individual Orders)
    // Fetch specific time and order ID for granular visualization
    $stmtChart = $pdo->prepare("
        SELECT 
            created_at, 
            grand_total as total_amount,
            increment_id
        FROM sales_order 
        WHERE customer_email = ?
        ORDER BY created_at ASC
    ");
    $stmtChart->execute([$user_email]);
    $chart_data = $stmtChart->fetchAll(PDO::FETCH_ASSOC);

    // 5. Response
    echo json_encode([
        'kpi' => [
            'total_orders' => $total_orders,
            'total_spent' => floatval($total_spent)
        ],
        'chart' => $chart_data
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
