<?php
session_start();
include "connect.php";

if (!isset($_SESSION['username'])) {
    header('location: loginPage.php');
    exit();
}
$username = $_SESSION['username'];

// Mengambil id akun dari username
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

// Mengambil transaksi
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
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

        .transaction-table td {
            padding: 12px 15px;
        } 

        .income-row .amount {
            color: var(--income);
            font-weight: 600;
        }

        .expense-row .amount {
            color: var(--expense);
            font-weight: 600;
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

        .transaction-date {
            color: #777;
            font-size: 1rem;
            margin-top: 5px;
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
    <div class="transactions-container">
        <div class="transactions-header">
            <h1><i class="bi bi-list-ul"></i> Daftar Transaksi</h1>
        </div>
            <table class="transaction-table table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Kategori</th>
                        <th>Catatan</th>
                        <th>Jumlah</th>
                        <th>Update</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($transaction = $transactions->fetch_assoc()): ?>
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
                            <td>
  <div class="d-flex w-75">
    <a href="edit_transaction.php?id=<?= $transaction['id'] ?>" class="btn btn-sm btn-primary w-50 me-2">
      <i class="fas fa-edit"></i> Edit
    </a>
    <a href="delete_transaction.php?id=<?= $transaction['id'] ?>" class="btn btn-sm btn-danger w-50" onclick="return confirm('Yakin ingin menghapus?')">
      <i class="fas fa-trash-alt"></i> Hapus
    </a>
  </div>
</td>

                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        <div class="btn-back">
                <a href="dashboard.php" class="kembali">
                     Kembali
                </a>
            </div>
    </div>
</body>

</html>