<?php
session_start();
include "connect.php";

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
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

// Periksa apakah ID transaksi dikirim
if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID transaksi tidak ditemukan.</div>";
    exit();
}

$transaction_id = $_GET['id'];

// Pastikan transaksi milik user yang sedang login
$sql = "DELETE FROM transactions WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $transaction_id, $user_id);

if ($stmt->execute()) {
    header("Location: transactions.php");
    exit();
} else {
    echo "<div class='alert alert-danger'>Gagal menghapus transaksi!</div>";
}
?>
