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
    <title>Finance Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #2E7D32;
            --secondary: #1565C0;
            --accent: #FF8F00;
            --light: #f5f5f5;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .header-section {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .user-greeting {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .user-tag {
            display: inline-block;
            background-color: var(--primary);
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-size: 1rem;
        }
        
        .feature-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            border-top: 4px solid;
            height: 100%;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }
        
        .income-card {
            border-top-color: var(--primary);
        }
        
        .history-card {
            border-top-color: var(--secondary);
        }
        
        .stats-card {
            border-top-color: var(--accent);
        }
        
        .feature-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: var(--primary);
        }
        
        .history-card .feature-icon {
            color: var(--secondary);
        }
        
        .stats-card .feature-icon {
            color: var(--accent);
        }
        
        .feature-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }
        
        .feature-desc {
            color: #666;
            margin-bottom: 1.5rem;
        }
        
        .feature-btn {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .income-btn {
            background-color: var(--primary);
            color: white;
        }
        
        .history-btn {
            background-color: var(--secondary);
            color: white;
        }
        
        .stats-btn {
            background-color: var(--accent);
            color: white;
        }
        
        .feature-btn:hover {
            opacity: 0.9;
            color: white;
            transform: translateX(5px);
        }
        
        .logout-btn {
            display: block;
            width: 100%;
            max-width: 200px;
            margin: 2rem auto 0;
            padding: 0.75rem;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover {
            background-color: #c82333;
            transform: translateY(-5px);
            color: white;
        }
        
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 1.5rem;
            }
            
            .user-greeting {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="header-section">
            <h2 class="user-greeting">Hello, <?= htmlspecialchars($_SESSION['username']) ?></h2>
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <div class="feature-card income-card">
                    <div class="feature-icon">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <h3 class="feature-title">Add Transaction</h3>
                    <p class="feature-desc">Record new income or expense transactions</p>
                    <a href="add_transaction.php" class="feature-btn income-btn">
                        Go <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="feature-card history-card">
                    <div class="feature-icon">
                        <i class="bi bi-journal-bookmark"></i>
                    </div>
                    <h3 class="feature-title">Transaction History</h3>
                    <p class="feature-desc">View all your financial records</p>
                    <a href="transactions.php" class="feature-btn history-btn">
                        Go <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="feature-card stats-card">
                    <div class="feature-icon">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <h3 class="feature-title">Financial Stats</h3>
                    <p class="feature-desc">See your financial overview</p>
                    <a href="stats.php" class="feature-btn stats-btn">
                        Go <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <a href="logout.php" class="logout-btn">
            <i class="bi bi-box-arrow-left"></i> Logout
        </a>
    </div>
</body>
</html>