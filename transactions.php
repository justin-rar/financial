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

// Get transactions
$sql = "SELECT * FROM transactions WHERE user_id = ? ORDER BY date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$transactions = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Transaksi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #2E7D32;
            --income: #4CAF50;
            --expense: #F44336;
            --light: #f5f5f5;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }
        
        .transactions-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        .transactions-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .transactions-header h1 {
            color: var(--primary);
            font-weight: 600;
        }
        
        .transaction-item {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border-left: 4px solid;
        }
        
        .transaction-income {
            border-color: var(--income);
        }
        
        .transaction-expense {
            border-color: var(--expense);
        }
        
        .transaction-date {
            font-size: 14px;
            color: #666;
        }
        
        .transaction-category {
            font-weight: 500;
        }
        
        .transaction-note {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
        
        .transaction-amount {
            font-weight: 600;
            text-align: right;
        }
        
        .income-amount {
            color: var(--income);
        }
        
        .expense-amount {
            color: var(--expense);
        }
        
        .back-btn {
            display: inline-flex;
            align-items: center;
            color: #1565C0;
            text-decoration: none;
            margin-top: 20px;
            transition: all 0.3s;
        }
        
        .back-btn:hover {
            color: #0D47A1;
            transform: translateX(-3px);
        }
        
        .no-transactions {
            text-align: center;
            padding: 30px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="transactions-container">
        <div class="transactions-header">
            <h1><i class="bi bi-list-check"></i> Daftar Transaksi</h1>
        </div>
        
        <?php if ($transactions->num_rows > 0): ?>
            <?php while($transaction = $transactions->fetch_assoc()): ?>
                <div class="transaction-item transaction-<?= $transaction['type'] ?>">
                    <div class="transaction-details">
                        <div class="transaction-date">
                            <?= date('d M Y', strtotime($transaction['date'])) ?>
                        </div>
                        <div class="transaction-category">
                            <?= htmlspecialchars($transaction['category']) ?>
                        </div>
                        <?php if (!empty($transaction['note'])): ?>
                            <div class="transaction-note">
                                <?= htmlspecialchars($transaction['note']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="transaction-amount <?= $transaction['type'] ?>-amount">
                        <?= $transaction['type'] == 'income' ? '+' : '-' ?>
                        Rp <?= number_format($transaction['amount'], 0, ',', '.') ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-transactions">
                <i class="bi bi-wallet2" style="font-size: 2rem; margin-bottom: 10px;"></i>
                <p>Belum ada transaksi yang tercatat</p>
            </div>
        <?php endif; ?>
        
        <a href="dashboard.php" class="back-btn">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
</body>
</html>