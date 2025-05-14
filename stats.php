<?php
session_start();
include "connect.php";

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];

// Get account_id from username
$sql = "SELECT account_id FROM tb_account WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "<div class='alert alert-danger'>User not found!</div>";
    exit();
}

$user_id = $user['account_id'];

// Get total income and expense
$sql_income = "SELECT SUM(amount) AS total FROM transactions WHERE user_id = ? AND type = 'income'";
$stmt = $conn->prepare($sql_income);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$income_result = $stmt->get_result();
$total_income = $income_result->fetch_assoc()['total'] ?? 0;

$sql_expense = "SELECT SUM(amount) AS total FROM transactions WHERE user_id = ? AND type = 'expense'";
$stmt = $conn->prepare($sql_expense);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$expense_result = $stmt->get_result();
$total_expense = $expense_result->fetch_assoc()['total'] ?? 0;

$balance = $total_income - $total_expense;

// Get monthly data for charts
$sql_monthly = "SELECT 
    DATE_FORMAT(date, '%Y-%m') AS month,
    SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) AS income,
    SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) AS expense
    FROM transactions 
    WHERE user_id = ?
    GROUP BY DATE_FORMAT(date, '%Y-%m')
    ORDER BY month DESC
    LIMIT 6";

$stmt = $conn->prepare($sql_monthly);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$monthly_result = $stmt->get_result();
$monthly_data = $monthly_result->fetch_all(MYSQLI_ASSOC);

// Get category data
$sql_categories = "SELECT 
    category,
    SUM(amount) AS total,
    type
    FROM transactions 
    WHERE user_id = ?
    GROUP BY category, type
    ORDER BY total DESC";

$stmt = $conn->prepare($sql_categories);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$categories_result = $stmt->get_result();
$categories_data = $categories_result->fetch_all(MYSQLI_ASSOC);

// Prepare data for charts
$months = [];
$income_data = [];
$expense_data = [];

foreach (array_reverse($monthly_data) as $row) {
    $months[] = date('M Y', strtotime($row['month'] . '-01'));
    $income_data[] = $row['income'];
    $expense_data[] = $row['expense'];
}

$income_categories = [];
$income_amounts = [];
$expense_categories = [];
$expense_amounts = [];

foreach ($categories_data as $row) {
    if ($row['type'] == 'income') {
        $income_categories[] = $row['category'];
        $income_amounts[] = $row['total'];
    } else {
        $expense_categories[] = $row['category'];
        $expense_amounts[] = $row['total'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Statistics</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #2E7D32;
            --secondary: #1565C0;
            --income: #388E3C;
            --expense: #D32F2F;
            --light: #f5f5f5;
            --dark: #212121;
            --gray: #757575;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .stats-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }
        
        .header-card {
            background-color: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border-left: 5px solid var(--primary);
        }
        
        .header-title {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .summary-card {
            background-color: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
            height: 100%;
        }
        
        .summary-title {
            font-size: 1rem;
            color: var(--gray);
            margin-bottom: 0.5rem;
        }
        
        .summary-value {
            font-size: 1.75rem;
            font-weight: 600;
        }
        
        .income-value {
            color: var(--income);
        }
        
        .expense-value {
            color: var(--expense);
        }
        
        .balance-value {
            color: var(--primary);
        }
        
        .chart-card {
            background-color: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }
        
        .chart-title {
            color: var(--dark);
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #eee;
        }
        
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        
        .back-btn {
            display: inline-flex;
            align-items: center;
            color: var(--secondary);
            text-decoration: none;
            margin-top: 1rem;
            transition: all 0.3s;
        }
        
        .back-btn:hover {
            color: #0D47A1;
            transform: translateX(-3px);
        }
        
        .tab-content {
            padding: 1.5rem 0;
        }
        
        .nav-tabs .nav-link {
            color: var(--gray);
            font-weight: 500;
            border: none;
            padding: 0.75rem 1.5rem;
        }
        
        .nav-tabs .nav-link.active {
            color: var(--primary);
            border-bottom: 3px solid var(--primary);
            background: transparent;
        }
    </style>
</head>
<body>
    <div class="stats-container">
        <div class="header-card">
            <h1 class="header-title"><i class="bi bi-graph-up"></i> Financial Statistics</h1>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="summary-card">
                        <div class="summary-title">Total Income</div>
                        <div class="summary-value income-value">Rp <?= number_format($total_income, 2, ',', '.') ?></div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="summary-card">
                        <div class="summary-title">Total Expense</div>
                        <div class="summary-value expense-value">Rp <?= number_format($total_expense, 2, ',', '.') ?></div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="summary-card">
                        <div class="summary-title">Current Balance</div>
                        <div class="summary-value balance-value">Rp <?= number_format($balance, 2, ',', '.') ?></div>
                    </div>
                </div>
            </div>
        </div>
        
        <ul class="nav nav-tabs" id="statsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="monthly-tab" data-bs-toggle="tab" data-bs-target="#monthly" type="button" role="tab">Monthly Trends</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="income-tab" data-bs-toggle="tab" data-bs-target="#income" type="button" role="tab">Income Categories</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="expense-tab" data-bs-toggle="tab" data-bs-target="#expense" type="button" role="tab">Expense Categories</button>
            </li>
        </ul>
        
        <div class="tab-content" id="statsTabsContent">
            <div class="tab-pane fade show active" id="monthly" role="tabpanel">
                <div class="chart-card">
                    <h3 class="chart-title">Income vs Expense (Last 6 Months)</h3>
                    <div class="chart-container">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="tab-pane fade" id="income" role="tabpanel">
                <div class="chart-card">
                    <h3 class="chart-title">Income by Category</h3>
                    <div class="chart-container">
                        <canvas id="incomeChart"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="tab-pane fade" id="expense" role="tabpanel">
                <div class="chart-card">
                    <h3 class="chart-title">Expense by Category</h3>
                    <div class="chart-container">
                        <canvas id="expenseChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <a href="dashboard.php" class="back-btn">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Monthly Trends Chart
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        const monthlyChart = new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($months) ?>,
                datasets: [
                    {
                        label: 'Income',
                        data: <?= json_encode($income_data) ?>,
                        backgroundColor: '#388E3C',
                        borderColor: '#2E7D32',
                        borderWidth: 1
                    },
                    {
                        label: 'Expense',
                        data: <?= json_encode($expense_data) ?>,
                        backgroundColor: '#D32F2F',
                        borderColor: '#B71C1C',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': Rp ' + context.raw.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
        
        // Income Categories Chart
        const incomeCtx = document.getElementById('incomeChart').getContext('2d');
        const incomeChart = new Chart(incomeCtx, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($income_categories) ?>,
                datasets: [{
                    data: <?= json_encode($income_amounts) ?>,
                    backgroundColor: [
                        '#388E3C', '#4CAF50', '#81C784', '#A5D6A7', '#C8E6C9'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': Rp ' + context.raw.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
        
        // Expense Categories Chart
        const expenseCtx = document.getElementById('expenseChart').getContext('2d');
        const expenseChart = new Chart(expenseCtx, {
            type: 'pie',
            data: {
                labels: <?= json_encode($expense_categories) ?>,
                datasets: [{
                    data: <?= json_encode($expense_amounts) ?>,
                    backgroundColor: [
                        '#D32F2F', '#F44336', '#E57373', '#EF9A9A', '#FFCDD2'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': Rp ' + context.raw.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>