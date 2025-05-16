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
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .transactions-header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .transactions-header h1 {
            color: var(--primary);
            font-weight: 600;
            font-size: 1.8rem;
        }
        
        .transaction-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .transaction-table th {
            background-color: #f8f9fa;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            color: #555;
            border-bottom: 2px solid #eee;
        }
        
        .transaction-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }
        
        .transaction-table tr:last-child td {
            border-bottom: none;
        }
        
        .transaction-table tr:hover {
            background-color: #f9f9f9;
        }
        
        .income-row .amount {
            color: var(--income);
            font-weight: 600;
        }
        
        .expense-row .amount {
            color: var(--expense);
            font-weight: 600;
        }
        
        .transaction-date {
            color: #666;
            font-size: 0.9rem;
        }
        
        .transaction-type {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: capitalize;
        }
        
        .type-income {
            background-color: rgba(76, 175, 80, 0.1);
            color: var(--income);
        }
        
        .type-expense {
            background-color: rgba(244, 67, 54, 0.1);
            color: var(--expense);
        }
        
        .transaction-note {
            color: #777;
            font-size: 0.9rem;
            margin-top: 5px;
        }
        
        .no-transactions {
            text-align: center;
            padding: 40px;
            color: #666;
            border-bottom: 1px solid #eee;
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
        
        @media (max-width: 768px) {
            .transaction-table {
                display: block;
                overflow-x: auto;
            }
            
            .transactions-header h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="transactions-container">
        <div class="transactions-header">
            <h1><i class="bi bi-list-ul"></i> Daftar Transaksi</h1>
        </div>
        
        <?php if ($transactions->num_rows > 0): ?>
            <table class="transaction-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Kategori</th>
                        <th>Catatan</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($transaction = $transactions->fetch_assoc()): ?>
                        <tr class="<?= $transaction['type'] ?>-row">
                            <td>
                                <div class="transaction-date">
                                    <?= date('d M Y', strtotime($transaction['date'])) ?>
                                </div>
                            </td>
                            <td>
                                <span class="transaction-type type-<?= $transaction['type'] ?>">
                                    <?= $transaction['type'] == 'income' ? 'Pemasukan' : 'Pengeluaran' ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($transaction['category']) ?></td>
                            <td>
                                <?php if (!empty($transaction['note'])): ?>
                                    <div class="transaction-note">
                                        <?= htmlspecialchars($transaction['note']) ?>
                                    </div>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="amount">
                                <?= $transaction['type'] == 'income' ? '+' : '-' ?>
                                Rp <?= number_format($transaction['amount'], 0, ',', '.') ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
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