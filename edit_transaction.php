<?php
session_start();
include "connect.php";

if (!isset($_SESSION['username'])) {
    header('location: loginPage.php');
    exit();
}

$username = $_SESSION['username'];

// Ambil account_id dari username
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

if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID transaksi tidak ditemukan.</div>";
    exit();
}

$transaction_id = $_GET['id'];

// Ambil data transaksi untuk diedit
$sql = "SELECT * FROM transactions WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $transaction_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$transaction = $result->fetch_assoc();

if (!$transaction) {
    echo "<div class='alert alert-danger'>Transaksi tidak ditemukan.</div>";
    exit();
}

// Proses update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'];
    $type = $_POST['type'];
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $note = $_POST['note'];

    $sql = "UPDATE transactions SET date = ?, type = ?, category = ?, amount = ?, note = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssdsii", $date, $type, $category, $amount, $note, $transaction_id, $user_id);

    if ($stmt->execute()) {
        header("Location: transactions.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Gagal mengupdate transaksi!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Transaksi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/addTransaction.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Edit Transaksi</h2>
        </div>

        <form method="POST" action="update.php">
            <input type="hidden" name="id" value="<?= $transaction_id ?>">
            <div class="type-selector">
                <div class="type-option income">
                    <input type="radio" id="type-income" name="type" value="income" <?= $transaction['type'] === 'income' ? 'checked' : '' ?>>
                    <label for="type-income"><i class="bi bi-arrow-down-circle"></i> Pemasukan</label>
                </div>
                <div class="type-option expense">
                    <input type="radio" id="type-expense" name="type" value="expense" <?= $transaction['type'] === 'expense' ? 'checked' : '' ?>>
                    <label for="type-expense"><i class="bi bi-arrow-up-circle"></i> Pengeluaran</label>
                </div>
            </div>

            <div class="form-group">
                <label for="date" class="form-label">Tanggal</label>
                <input type="date" class="form-jawab" id="date" name="date" required value="<?= $transaction['date'] ?>">
            </div>

            <div class="form-group">
                <label for="category" class="form-label">Kategori</label>
                <input type="text" class="form-jawab" id="category" name="category" value="<?= $transaction['category'] ?>" required>
            </div>

            <div class="form-group">
                <label for="amount" class="form-label">Jumlah</label>
                <input type="number" class="form-jawab" id="amount" name="amount" step="0.01" value="<?= $transaction['amount'] ?>" required>
            </div>

            <div class="form-group">
                <label for="note" class="form-label">Catatan (Opsional)</label>
                <textarea class="form-jawab" id="note" name="note" rows="3"><?= $transaction['note'] ?></textarea>
            </div>

            <button type="submit" class="btn-submit">Update Transaksi</button>

            <div class="btn-back">
                <a href="transactions.php" class="kembali">Kembali</a>
            </div>
        </form>
    </div>
</body>
</html>
