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
        
        .transaction-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .transaction-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .transaction-header h2 {
            color: var(--primary);
            font-weight: 600;
        }
        
        /* Type Selector Styles */
        .type-selector {
            display: flex;
            margin-bottom: 20px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #ddd;
        }
        
        .type-option {
            flex: 1;
            position: relative;
        }
        
        .type-option input[type="radio"] {
            position: absolute;
            opacity: 0;
        }
        
        .type-option label {
            display: block;
            padding: 12px;
            text-align: center;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            background-color: #f9f9f9;
        }
        
        /* Hover Effects */
        .type-option.income label:hover {
            background-color: var(--income);
            color: white;
        }
        
        .type-option.expense label:hover {
            background-color: var(--expense);
            color: white;
        }
        
        /* Selected State */
        .type-option input[type="radio"]:checked + label {
            color: white;
        }
        
        .type-option.income input[type="radio"]:checked + label {
            background-color: var(--income);
        }
        
        .type-option.expense input[type="radio"]:checked + label {
            background-color: var(--expense);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
        }
        
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            transition: border 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            outline: none;
        }
        
        .btn-submit {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 6px;
            font-weight: 500;
            transition: background 0.3s;
        }
        
        .btn-submit:hover {
            background-color: #1B5E20;
        }
        
        .back-link {
            display: inline-block;
            margin-top: 15px;
            color: #1565C0;
            text-decoration: none;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="transaction-container">
        <div class="transaction-header">
            <h2><i class="bi bi-plus-circle"></i> Add Transaction</h2>
        </div>
        
        <form method="POST">
            <div class="type-selector">
                <div class="type-option income">
                    <input type="radio" id="type-income" name="type" value="income" checked>
                    <label for="type-income"><i class="bi bi-arrow-down-circle"></i> Income</label>
                </div>
                <div class="type-option expense">
                    <input type="radio" id="type-expense" name="type" value="expense">
                    <label for="type-expense"><i class="bi bi-arrow-up-circle"></i> Expense</label>
                </div>
            </div>
            
            <div class="form-group">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" id="date" name="date" required value="<?php echo date('Y-m-d'); ?>">
            </div>
            
            <div class="form-group">
                <label for="category" class="form-label">Category</label>
                <input type="text" class="form-control" id="category" name="category" placeholder="e.g. Salary, Food, Transportation" required>
            </div>
            
            <div class="form-group">
                <label for="amount" class="form-label">Amount</label>
                <div style="display: flex;">
                    <span style="padding: 10px; background: #eee; border: 1px solid #ddd; border-right: none; border-radius: 6px 0 0 6px;">Rp</span>
                    <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="0" placeholder="0.00" required style="border-radius: 0 6px 6px 0;">
                </div>
            </div>
            
            <div class="form-group">
                <label for="note" class="form-label">Notes (Optional)</label>
                <textarea class="form-control" id="note" name="note" rows="3" placeholder="Additional information"></textarea>
            </div>
            
            <button type="submit" class="btn-submit">
                <i class="bi bi-save"></i> Save Transaction
            </button>
            
            <a href="dashboard.php" class="back-link">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
        </form>
    </div>
</body>
</html>