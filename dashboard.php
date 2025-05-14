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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Finance Dashboard</title>
    <style>
        :root {
            --primary: #2E7D32;
            --primary-light: #81C784;
            --secondary: #1565C0;
            --accent: #FF8F00;
            --dark: #263238;
            --light: #ECEFF1;
            --white: #FFFFFF;
        }
        
        body {
            background-color: var(--light);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--dark);
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.18);
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .dashboard-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }
        
        .header-section {
            margin-bottom: 3rem;
            text-align: center;
        }
        
        .user-greeting {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        
        .user-tag {
            display: inline-block;
            background-color: var(--primary);
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-size: 1.1rem;
            box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
        }
        
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .feature-card {
            padding: 2rem;
            border-top: 5px solid;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }
        
        .feature-card.income {
            border-color: var(--primary);
        }
        
        .feature-card.history {
            border-color: var(--secondary);
        }
        
        .feature-card.stats {
            border-color: var(--accent);
        }
        
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
        }
        
        .income .feature-icon {
            color: var(--primary);
        }
        
        .history .feature-icon {
            color: var(--secondary);
        }
        
        .stats .feature-icon {
            color: var(--accent);
        }
        
        .feature-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .feature-desc {
            color: var(--dark);
            opacity: 0.8;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .feature-btn {
            display: inline-flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .income .feature-btn {
            background-color: var(--primary);
            color: white;
        }
        
        .history .feature-btn {
            background-color: var(--secondary);
            color: white;
        }
        
        .stats .feature-btn {
            background-color: var(--accent);
            color: white;
        }
        
        .feature-btn:hover {
            transform: translateX(5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }
        
        .feature-btn i {
            margin-left: 0.5rem;
            transition: transform 0.3s ease;
        }
        
        .feature-btn:hover i {
            transform: translateX(3px);
        }
        
        .logout-section {
            text-align: center;
            margin-top: 4rem;
            position: relative;
        }
        
        .logout-btn {
            display: inline-flex;
            align-items: center;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, #D32F2F 0%, #B71C1C 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 500;
            font-size: 1.1rem;
            box-shadow: 0 8px 24px rgba(211, 47, 47, 0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .logout-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #B71C1C 0%, #D32F2F 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }
        
        .logout-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(211, 47, 47, 0.4);
        }
        
        .logout-btn:hover::before {
            opacity: 1;
        }
        
        .logout-btn i {
            margin-right: 0.75rem;
            font-size: 1.2rem;
        }
        
        .logout-decoration {
            position: absolute;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(211, 47, 47, 0.08);
            z-index: -1;
        }
        
        .decoration-1 {
            top: -50px;
            left: -50px;
        }
        
        .decoration-2 {
            bottom: -50px;
            right: -50px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="header-section">
            <h1 class="user-greeting">Hello, <?= htmlspecialchars($_SESSION['username']) ?></h1>
            <div class="user-tag">
                <i class="bi bi-shield-check"></i> Verified Account
            </div>
        </div>
        
        <div class="feature-grid">
            <div class="glass-card feature-card income">
                <div class="feature-icon">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <h3 class="feature-title">Transaction Manager</h3>
                <p class="feature-desc">Record all your financial transactions with detailed categories and notes.</p>
                <a href="add_transaction.php" class="feature-btn">
                    Add Transaction <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            
            <div class="glass-card feature-card history">
                <div class="feature-icon">
                    <i class="bi bi-journal-bookmark"></i>
                </div>
                <h3 class="feature-title">Transaction History</h3>
                <p class="feature-desc">View and analyze your complete financial history in one place.</p>
                <a href="transactions.php" class="feature-btn">
                    View History <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            
            <div class="glass-card feature-card stats">
                <div class="feature-icon">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <h3 class="feature-title">Financial Analytics</h3>
                <p class="feature-desc">Get insights into your spending patterns and financial health.</p>
                <a href="stats.php" class="feature-btn">
                    View Analytics <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
        
        <div class="logout-section">
            <div class="logout-decoration decoration-1"></div>
            <button class="logout-btn">
                <i class="bi bi-box-arrow-left"></i> Sign Out
            </button>
            <div class="logout-decoration decoration-2"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelector('.logout-btn').addEventListener('click', function() {
            window.location.href = 'logout.php';
        });
    </script>
</body>
</html>