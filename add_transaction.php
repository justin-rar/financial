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
    echo "User not found!";
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
            --primary-light: #81C784;
            --secondary: #1565C0;
            --accent: #FF8F00;
            --dark: #263238;
            --light: #ECEFF1;
            --success: #4CAF50;
            --danger: #F44336;
            --warning: #FFC107;
            --info: #2196F3;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .transaction-container {
            max-width: 600px;
            width: 100%;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }
        
        .transaction-card {
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            padding: 2.5rem;
            border-top: 5px solid var(--primary);
            transition: all 0.3s ease;
        }
        
        .transaction-card:hover {
            box-shadow: 0 15px 35px rgba(0,0,0,0.12);
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .form-header h2 {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 0.5rem;
            position: relative;
            display: inline-block;
        }
        
        .form-header h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 25%;
            width: 50%;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--primary), transparent);
            border-radius: 3px;
        }
        
        .form-header p {
            color: #616161;
            margin-top: 1rem;
        }
        
        .form-label {
            font-weight: 500;
            color: #424242;
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .form-control, .form-select {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s;
            width: 100%;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(46, 125, 50, 0.2);
            outline: none;
        }
        
        .btn-submit {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            width: 100%;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .btn-submit:hover {
            background-color: #1B5E20;
            transform: translateY(-2px);
        }
        
        .btn-back {
            display: inline-flex;
            align-items: center;
            color: var(--secondary);
            text-decoration: none;
            margin-top: 1.5rem;
            transition: all 0.3s;
            gap: 0.5rem;
        }
        
        .btn-back:hover {
            color: #0D47A1;
            transform: translateX(-3px);
        }
        
        .type-selector {
            display: flex;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e0e0e0;
        }
        
        .type-option {
            flex: 1;
            text-align: center;
            padding: 0.75rem;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }
        
        .type-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .type-option label {
            display: block;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .type-option:hover {
            background-color: #f5f5f5;
        }
        
        .type-option input[type="radio"]:checked + label {
            color: white;
        }
        
        .type-option.income input[type="radio"]:checked + label {
            background-color: var(--success);
        }
        
        .type-option.expense input[type="radio"]:checked + label {
            background-color: var(--danger);
        }
        
        .input-group {
            display: flex;
            align-items: center;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e0e0e0;
        }
        
        .input-group-text {
            background-color: #e0e0e0;
            border: none;
            padding: 0.75rem 1rem;
            font-weight: 500;
        }
        
        .input-group input {
            border: none;
            border-radius: 0;
        }
        
        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }
        
        /* Set default date using HTML only */
        input[type="date"]:not([value]) {
            color: #757575;
        }
        
        @media (max-width: 576px) {
            .transaction-container {
                padding: 0 1rem;
            }
            
            .transaction-card {
                padding: 1.5rem;
            }
            
            .type-selector {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="transaction-container">
        <div class="transaction-card">
            <div class="form-header">
                <h2><i class="bi bi-plus-circle"></i> Add New Transaction</h2>
                <p>Record your financial activity</p>
            </div>
            
            <form method="POST">
                <div class="mb-4">
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
                </div>
                
                <div class="mb-4">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" class="form-control" id="date" name="date" required value="<?php echo date('Y-m-d'); ?>">
                </div>
                
                <div class="mb-4">
                    <label for="category" class="form-label">Category</label>
                    <input type="text" class="form-control" id="category" name="category" placeholder="e.g. Salary, Food, Transportation" required>
                </div>
                
                <div class="mb-4">
                    <label for="amount" class="form-label">Amount</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="0" placeholder="0.00" required>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="note" class="form-label">Notes (Optional)</label>
                    <textarea class="form-control" id="note" name="note" rows="3" placeholder="Additional information about this transaction"></textarea>
                </div>
                
                <button type="submit" class="btn btn-submit mb-3">
                    <i class="bi bi-save"></i> Save Transaction
                </button>
                
                <a href="dashboard.php" class="btn-back">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </form>
        </div>
    </div>
</body>
</html>