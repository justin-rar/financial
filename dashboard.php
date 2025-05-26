<?php
session_start();
include "connect.php";
if (!isset($_SESSION["username"])) { 
    header("Location: login.php"); 
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

// Ambil totla income dan pengeluarannya
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
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <title>Dashboard</title>
</head>
<body>
    <nav class="navbar">
    <div class="logo">Bebet Financial Tracker</div>
    <div class="link">
    <a href="index.html" class="home-link home"><i class="fa fa-home"></i> Beranda</a>
    <a href="logout.php" class="home-link logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>
    
</nav>

<div class="row">
    <div class="dashboard-container">
        <div class="header-section">
            <h2 class="salam">Halo, <?= htmlspecialchars($_SESSION['username']) ?> selamat datang di Bebet!</h2>
        </div>

       <div class="card-section summary-section">
    <div class="card summary-card income">
        <div class="card-content">
            <h4>Pemasukan</h4>
            <p>Rp <?= number_format($total_income, 0, ',', '.') ?></p>
        </div>
    </div>
    <div class="card summary-card expense">
        <div class="card-content">
            <h4>Pengeluaran</h4>
            <p>Rp <?= number_format($total_expense, 0, ',', '.') ?></p>
        </div>
    </div>
    <div class="card summary-card balance">
        <div class="card-content">
            <h4>Saldo</h4>
            <p>Rp <?= number_format($balance, 0, ',', '.') ?></p>
        </div>
    </div>
</div>


        <div class="card-section">
            <div class="card">
                <div class="card-content">
                    <div class="icon i-add">
                    <i class="fa-solid fa-plus"></i>
                </div>
                <h3>Tambah Transaksi</h3>
                <p>Catat pemasukan atau pengeluaran harian <br>secara praktis.</p>

                <a href="add_transaction.php" class="btn-card add-btn">Tambah</a>
                </div>
            </div>
            <div class="card">
                <div class="card-content">
                    <div class="icon i-his">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                    <h3>Lihat Transaksi</h3>
                    <p>Lihat riwayat lengkap semua transaksi <br>keuangan Anda.</p>
                    <a href="transactions.php" class="btn-card histo-btn">Cek</a>
                </div>
            </div>
            <div class="card">
                <div class="card-content">
                    <div class="icon i-stats">
                    <i class="fa-solid fa-chart-bar"></i>
                </div>
                <h3>Lihat Statistik</h3>
                <p>Tinjau grafik keuangan untuk evaluasi <br>pengeluaran dan tabungan.</p>
                <a href="stats.php" class="btn-card stats-btn">Lihat</a>
                </div>
                
            </div>
        </div>

        <div class="quotes">
    <blockquote>"Catat keuanganmu layaknya seorang <strong>bos muda!</strong>"</blockquote>
</div>

    
</html>