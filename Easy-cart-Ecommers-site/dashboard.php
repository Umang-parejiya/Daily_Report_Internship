<?php
require_once 'config/session.php';
require_once 'config/db.php';
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
    fetch('get_dashboard_data.php')
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
