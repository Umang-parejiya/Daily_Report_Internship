<?php
require_once 'config/session.php';
require_once 'config/db.php';

// Handle AJAX Request for Dashboard Data
if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    header('Content-Type: application/json');

    // 1. Auth Check - re-check because config/auth.php redirects, but for JSON we want 401
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    $user_id = $_SESSION['user_id'];

    try {
        // 2. Get User Email
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

        // 4. Chart Data
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
    exit; // STOP!
}

require_once 'config/auth.php'; // Enforce login


$page_title = 'Easy-Cart - Dashboard';
$current_page = 'dashboard';

include 'includes/header.php';
?>

<div class="container">
    <section class="section">
        <div class="section-header">
            <h1 class="section-title">Dashboard</h1>
            <p class="section-subtitle">Overview of your shopping activity</p>
        </div>

        <!-- 1. KPI Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
            <!-- KPI: Total Orders -->
            <div class="card" style="padding: 1.5rem; display: flex; flex-direction: column; gap: 0.5rem; border-left: 4px solid var(--primary);">
                <div style="font-size: 0.875rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Total Orders</div>
                <div style="font-size: 2rem; font-weight: 700; color: var(--text-primary);" id="kpi-orders">-</div>
                <div style="font-size: 0.85rem; color: var(--text-secondary);">Lifetime orders placed</div>
            </div>

            <!-- KPI: Total Spent -->
            <div class="card" style="padding: 1.5rem; display: flex; flex-direction: column; gap: 0.5rem; border-left: 4px solid var(--accent);">
                <div style="font-size: 0.875rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Total Spent</div>
                <div style="font-size: 2rem; font-weight: 700; color: var(--text-primary);" id="kpi-spent">-</div>
                <div style="font-size: 0.85rem; color: var(--text-secondary);">Lifetime amount spent</div>
            </div>
        </div>

        <!-- 2. Chart Section -->
        <div class="card" style="padding: 2rem;">
            <div style="margin-bottom: 2rem;">
                <h3 style="margin-bottom: 0.5rem;">Spending History</h3>
                <p style="color: var(--text-secondary); font-size: 0.9rem;">Your daily spending over time</p>
            </div>
            
            <div style="position: relative; height: 400px; width: 100%;">
                <canvas id="spendingChart"></canvas>
            </div>
            <!-- Loading State -->
            <div id="chart-loading" style="text-align: center; padding: 2rem; color: var(--text-secondary);">
                Loading chart data...
            </div>
        </div>

    </section>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Fetch Dashboard Data
    fetch('dashboard.php?ajax=1')
        .then(response => {
            if (!response.ok) { // Check for 401 Unauthorized or other errors
                if (response.status === 401) {
                    window.location.href = 'login.php';
                }
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // 1. Update KPIs
            document.getElementById('kpi-orders').textContent = data.kpi.total_orders;
            document.getElementById('kpi-spent').textContent = '₹' + parseFloat(data.kpi.total_spent).toLocaleString('en-IN');

            // 2. Render Chart
            const ctx = document.getElementById('spendingChart').getContext('2d');
            
            // Prepare Data
            const labels = data.chart.map(item => {
                const date = new Date(item.created_at);
                // Format: "Feb 4, 10:30 PM"
                return date.toLocaleDateString('en-IN', { month: 'short', day: 'numeric' }) + ', ' + 
                       date.toLocaleTimeString('en-IN', { hour: '2-digit', minute: '2-digit' });
            });
            
            // Extract IDs for tooltip
            const orderIds = data.chart.map(item => item.increment_id);
            const values = data.chart.map(item => item.total_amount);

            document.getElementById('chart-loading').style.display = 'none';

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Order Amount (₹)',
                        data: values,
                        borderColor: '#2563eb', 
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        borderWidth: 2,
                        tension: 0, // Straight lines to show distinct orders better? Or keep curve? User said "design", usually curves are nicer but straight shows "points" better. Let's stick to slight curve.
                        fill: true,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#2563eb',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1f2937',
                            padding: 12,
                            titleFont: { size: 13 },
                            bodyFont: { size: 13 },
                            callbacks: {
                                title: function(tooltipItems) {
                                    // Show Order ID and Date
                                    const index = tooltipItems[0].dataIndex;
                                    return 'Order #' + orderIds[index] + ' (' + labels[index] + ')';
                                },
                                label: function(context) {
                                    return 'Amount: ₹' + Number(context.parsed.y).toLocaleString('en-IN');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f3f4f6' },
                            ticks: {
                                callback: function(value) { return '₹' + value; }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45
                            }
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error fetching dashboard data:', error);
            document.getElementById('chart-loading').textContent = 'Failed to load data.';
        });
});
</script>

<?php include 'includes/footer.php'; ?>
