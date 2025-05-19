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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'];
    $type = $_POST['type'];
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $note = $_POST['note'];

    $sql = "INSERT INTO transactions (user_id, date, type, category, amount, note) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssds", $user_id, $date, $type, $category, $amount, $note);

    if ($stmt->execute()) {
        header("Location: transactions.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Failed to save transaction!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Transaction</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/addTransaction.css">

</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Tambah Transaksi</h2>
        </div>
        
        <form method="POST">
            <div class="type-selector">
                <div class="type-option income">
                    <input type="radio" id="type-income" name="type" value="income" checked>
                    <label for="type-income"><i class="bi bi-arrow-down-circle"></i> Pemasukan</label>
                </div>
                <div class="type-option expense">
                    <input type="radio" id="type-expense" name="type" value="expense">
                    <label for="type-expense"><i class="bi bi-arrow-up-circle"></i> Pengeluaran</label>
                </div>
            </div>
            
            <div class="form-group">
                <label for="date" class="form-label">Tanggal</label>
                <input type="date" class="form-jawab" id="date" name="date" required value="<?php echo date('Y-m-d'); ?>">
            </div>
            
            <div class="form-group">
                <label for="category" class="form-label">Kategori</label>
                <input type="text" class="form-jawab" id="category" name="category" placeholder="Gaji, Makanan, Hiburan, dll." required>
            </div>
            
            <div class="form-group">
                <label for="amount" class="form-label">Jumlah</label>
                    <input type="number" class="form-jawab" id="amount" name="amount" placeholder="Rp. " required>
            </div>
            
            <div class="form-group">
                <label for="note" class="form-label">Catatan (Opsional)</label>
                <textarea class="form-jawab" id="note" name="note" rows="3" placeholder="Informasi Tambahan"></textarea>
            </div>
            
            <button type="submit" class="btn-submit">Simpan Transaksi</button>
            
            <div class="btn-back">
                <a href="dashboard.php" class="kembali">
                     Kembali
                </a>
            </div>
        </form>
    </div>
</body>
</html>