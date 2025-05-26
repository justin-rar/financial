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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $transaction_id = $_POST['id'];
    $date = $_POST['date'];
    $type = $_POST['type'];
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $note = $_POST['note'];

    $check = $conn->prepare("SELECT * FROM transactions WHERE id = ? AND user_id = ?");
    $check->bind_param("ii", $transaction_id, $user_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows === 0) {
        echo "<div class='alert alert-danger'>Transaksi tidak ditemukan atau bukan milik Anda.</div>";
        exit();
    }

    // update data
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
