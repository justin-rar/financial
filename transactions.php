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

// Get transactions with sorting
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'date_desc';
$order_by = 'date DESC'; // default

switch ($sort) {
    case 'date_asc':
        $order_by = 'date ASC';
        break;
    case 'amount_desc':
        $order_by = 'amount DESC';
        break;
    case 'amount_asc':
        $order_by = 'amount ASC';
        break;
}

$sql = "SELECT * FROM transactions WHERE user_id = ? ORDER BY $order_by";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Calculate totals
$total_income = 0;
$total_expense = 0;

while ($row = $result->fetch_assoc()) {
    if ($row['type'] == 'income') {
        $total_income += $row['amount'];
    } else {
        $total_expense += $row['amount'];
    }
}
$balance = $total_income - $total_expense;

// Reset pointer
$result->data_seek(0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        
        .transaction-container {
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
            margin-bottom: 1.5rem;
        }
        
        .summary-card {
            background-color: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
            height: 100%;
        }
        
        .summary-title {
            font-size: 0.9rem;
            color: var(--gray);
            margin-bottom: 0.5rem;
        }
        
        .summary-value {
            font-size: 1.5rem;
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
        
        .transaction-table {
            background-color: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        .table thead {
            background-color: var(--primary);
            color: white;
        }
        
        .table th {
            font-weight: 500;
            padding: 1rem;
        }
        
        .table td {
            padding: 1rem;
            vertical-align: middle;
        }
        
        .income-row {
            border-left: 4px solid var(--income);
        }
        
        .expense-row {
            border-left: 4px solid var(--expense);
        }
        
        .type-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .badge-income {
            background-color: rgba(56, 142, 60, 0.1);
            color: var(--income);
        }
        
        .badge-expense {
            background-color: rgba(211, 47, 47, 0.1);
            color: var(--expense);
        }
        
        .amount-income {
            color: var(--income);
            font-weight: 500;
        }
        
        .amount-expense {
            color: var(--expense);
            font-weight: 500;
        }
        
        .sort-link {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        
        .sort-link:hover {
            color: #e0e0e0;
        }
        
        .sort-icon {
            margin-left: 0.5rem;
            font-size: 0.8rem;
        }
        
        .back-btn {
            display: inline-flex;
            align-items: center;
            color: var(--secondary);
            text-decoration: none;
            margin-top: 2rem;
            transition: all 0.3s;
        }
        
        .back-btn:hover {
            color: #0D47A1;
            transform: translateX(-3px);
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--gray);
        }
        
        .empty-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #e0e0e0;
        }
        
        .action-btns .btn {
            margin-right: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="transaction-container">
        <div class="header-card">
            <h1 class="header-title"><i class="bi bi-journal-bookmark"></i> Transaction History</h1>
            
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
        
        <div class="transaction-table">
            <?php if ($result->num_rows > 0): ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>
                                <a href="?sort=<?= $sort == 'date_desc' ? 'date_asc' : 'date_desc' ?>" class="sort-link">
                                    Date
                                    <i class="bi bi-arrow-<?= $sort == 'date_desc' ? 'down' : 'up' ?> sort-icon"></i>
                                </a>
                            </th>
                            <th>Type</th>
                            <th>Category</th>
                            <th>
                                <a href="?sort=<?= in_array($sort, ['amount_desc', 'date_desc']) ? 'amount_asc' : 'amount_desc' ?>" class="sort-link">
                                    Amount
                                    <i class="bi bi-arrow-<?= in_array($sort, ['amount_desc', 'date_desc']) ? 'down' : 'up' ?> sort-icon"></i>
                                </a>
                            </th>
                            <th>Note</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr class="<?= $row['type'] == 'income' ? 'income-row' : 'expense-row' ?>">
                                <td><?= date('d M Y', strtotime($row['date'])) ?></td>
                                <td>
                                    <span class="type-badge <?= $row['type'] == 'income' ? 'badge-income' : 'badge-expense' ?>">
                                        <?= ucfirst($row['type']) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($row['category']) ?></td>
                                <td class="<?= $row['type'] == 'income' ? 'amount-income' : 'amount-expense' ?>">
                                    Rp <?= number_format($row['amount'], 2, ',', '.') ?>
                                </td>
                                <td><?= htmlspecialchars($row['note']) ?></td>
                                <td class="action-btns">
                                    <a href="edit_transaction.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="delete_transaction.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="bi bi-journal-x"></i>
                    </div>
                    <h4>No Transactions Found</h4>
                    <p>You haven't recorded any transactions yet</p>
                    <a href="add_transaction.php" class="btn btn-primary mt-3">
                        <i class="bi bi-plus-circle"></i> Add Your First Transaction
                    </a>
                </div>
            <?php endif; ?>
        </div>
        
        <a href="dashboard.php" class="back-btn">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>