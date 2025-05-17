<?php
session_start();
if (!isset($_SESSION["username"])) { 
    header("Location: login.php"); 
    exit(); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <title>Dashboard</title>
</head>
<body>
    <div class="dashboard-container">

        <div class="header-section">
            <h2 class="salam">Halo, <?= htmlspecialchars($_SESSION['username']) ?></h2>
        </div>

        <div class="card-section">
            <div class="card">
                <div class="icon i-add">
                    <i class="fa-solid fa-plus"></i>
                </div>
                <h3>Tambah Transaksi</h3>
                <p>Record new income or expense transactions</p>
                <a href="add_transaction.php" class="btn-card add-btn">Tambah</a>
            </div>
            <div class="card">
                <div class="icon i-his">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <h3>Lihat Transaksi</h3>
                <p>Record new income or expense transactions</p>
                <a href="transactions.php" class="btn-card histo-btn">Cek</a>
            </div>
            <div class="card">
                <div class="icon i-stats">
                    <i class="fa-solid fa-chart-bar"></i>
                </div>
                <h3>Lihat Statistik</h3>
                <p>Record new income or expense transactions</p>
                <a href="stats.php" class="btn-card stats-btn">Lihat</a>
            </div>
        </div>

        <a href="logout.php" class="logout-btn">Logout <i class="fa-solid fa-right-from-bracket"></i></a>

    </div>
</html>