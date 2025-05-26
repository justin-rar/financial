<?php
session_start();
include "connect.php";

if (!isset($_SESSION['username'])) {
    header('location: loginPage.php');
    exit();
}

$username = $_SESSION['username'];

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik Keuangan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #2E7D32;
            --income: #4CAF50;
            --expense: #F44336;
            --light: #f5f5f5;
        }
        
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        
        .stats-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        .stats-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .stats-header h1 {
            color: var(--primary);
            font-weight: 600;
        }
        
        .summary-card {
            background-color: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border-bottom: 4px solid;
        }
        
        .income-card {
            border-color: var(--income);
        }
        
        .expense-card {
            border-color: var(--expense);
        }
        
        .balance-card {
            border-color: var(--primary);
        }
        
        .summary-title {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .summary-value {
            font-size: 18px;
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
        
        .chart-container {
            height: 300px;
            margin: 20px 0;
            position: relative;
        }
        
        .back-btn {
            display: inline-flex;
            align-items: center;
            color: #1565C0;
            text-decoration: none;
            margin-top: 15px;
            transition: all 0.3s;
        }
        
        .back-btn:hover {
            color: #0D47A1;
            transform: translateX(-3px);
        }
        
        .btn-back {
            margin-top: 30px;
            text-align: center;
        }
        
        .kembali {
            font-size: 0.9rem;
            border-radius: 10px;
            padding: 8px;
            background-color: #F44336;
           text-align: center;
           color: white;
           text-decoration: none;
           transition: background 0.3s;
        }
        
        .kembali:hover{
            background-color: #962a22;
        }

    </style>
</head>
<body>
    <div class="stats-container">
        <div class="stats-header">
            <h1>Statistik Keuangan</h1>
        </div>
        
        <div class="summary-card income-card">
            <div class="summary-title">Pemasukan</div>
            <div class="summary-value income-value">Rp <?= number_format($total_income, 0, ',', '.') ?></div>
        </div>
        
        <div class="summary-card expense-card">
            <div class="summary-title">Pengeluaran</div>
            <div class="summary-value expense-value">Rp <?= number_format($total_expense, 0, ',', '.') ?></div>
        </div>
        
        <div class="summary-card balance-card">
            <div class="summary-title">Saldo</div>
            <div class="summary-value balance-value">Rp <?= number_format($balance, 0, ',', '.') ?></div>
        </div>
        
        <div class="chart-container">
            <canvas id="financeChart"></canvas>
        </div>
        
        <div class="btn-back">
                <a href="dashboard.php" class="kembali">
                     Kembali
                </a>
            </div>
    </div>

    <script>
        // Pie Chart
        const ctx = document.getElementById('financeChart').getContext('2d');
        const financeChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Pemasukan', 'Pengeluaran'],
                datasets: [{
                    data: [<?= $total_income ?>, <?= $total_expense ?>],
                    backgroundColor: ['#4CAF50', '#F44336'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
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