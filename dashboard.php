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
            --success: #4CAF50;
            --danger: #F44336;
            --warning: #FFC107;
            --info: #2196F3;
        }
        
        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--dark);
            min-height: 100vh;
        }
        
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }
        
        .header-section {
            margin-bottom: 3rem;
            text-align: center;
            position: relative;
            padding-bottom: 1.5rem;
        }
        
        .header-section::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 25%;
            width: 50%;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--primary), transparent);
            border-radius: 3px;
        }
        
        .user-greeting {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.5rem;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.1);
        }
        
        .user-tag {
            display: inline-block;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-size: 1.1rem;
            box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .user-tag::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                to bottom right,
                rgba(255,255,255,0.3) 0%,
                rgba(255,255,255,0) 60%
            );
            transform: rotate(30deg);
        }
        
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .feature-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 10px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            border: none;
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
        }
        
        .feature-card.income::before {
            background: linear-gradient(90deg, var(--success), var(--primary));
        }
        
        .feature-card.history::before {
            background: linear-gradient(90deg, var(--info), var(--secondary));
        }
        
        .feature-card.stats::before {
            background: linear-gradient(90deg, var(--warning), var(--accent));
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.12);
        }
        
        .feature-card:hover::after {
            opacity: 1;
        }
        
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .feature-card:hover .feature-icon {
            transform: scale(1.1);
        }
        
        .income .feature-icon {
            color: var(--success);
            text-shadow: 0 2px 5px rgba(76, 175, 80, 0.3);
        }
        
        .history .feature-icon {
            color: var(--info);
            text-shadow: 0 2px 5px rgba(33, 150, 243, 0.3);
        }
        
        .stats .feature-icon {
            color: var(--warning);
            text-shadow: 0 2px 5px rgba(255, 193, 7, 0.3);
        }
        
        .feature-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--dark);
        }
        
        .feature-desc {
            color: #616161;
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
            position: relative;
            overflow: hidden;
            border: none;
            z-index: 1;
        }
        
        .feature-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            transition: all 0.3s ease;
        }
        
        .income .feature-btn {
            background-color: var(--success);
            color: white;
        }
        
        .history .feature-btn {
            background-color: var(--info);
            color: white;
        }
        
        .stats .feature-btn {
            background-color: var(--warning);
            color: white;
        }
        
        .feature-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .feature-btn:hover::before {
            transform: scale(1.05);
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
            background: linear-gradient(135deg, var(--danger) 0%, #B71C1C 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 500;
            font-size: 1.1rem;
            box-shadow: 0 8px 24px rgba(211, 47, 47, 0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }
        
        .logout-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(211, 47, 47, 0.4);
            background: linear-gradient(135deg, #B71C1C 0%, var(--danger) 100%);
        }
        
        .logout-btn i {
            margin-right: 0.75rem;
            font-size: 1.2rem;
        }
        
        .decoration-circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(211, 47, 47, 0.08);
            z-index: -1;
        }
        
        .circle-1 {
            width: 150px;
            height: 150px;
            top: -50px;
            left: -50px;
        }
        
        .circle-2 {
            width: 200px;
            height: 200px;
            bottom: -50px;
            right: -50px;
        }
        
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 1.5rem 1rem;
            }
            
            .user-greeting {
                font-size: 2rem;
            }
            
            .feature-grid {
                grid-template-columns: 1fr;
            }
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
            <div class="feature-card income">
                <div class="feature-icon">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <h3 class="feature-title">Transaction Manager</h3>
                <p class="feature-desc">Record all your financial transactions with detailed categories and notes.</p>
                <a href="add_transaction.php" class="feature-btn">
                    Add Transaction <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            
            <div class="feature-card history">
                <div class="feature-icon">
                    <i class="bi bi-journal-bookmark"></i>
                </div>
                <h3 class="feature-title">Transaction History</h3>
                <p class="feature-desc">View and analyze your complete financial history in one place.</p>
                <a href="transactions.php" class="feature-btn">
                    View History <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            
            <div class="feature-card stats">
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
            <div class="decoration-circle circle-1"></div>
            <div class="decoration-circle circle-2"></div>
            <a href="logout.php" class="logout-btn">
                <i class="bi bi-box-arrow-left"></i> Sign Out
            </a>
        </div>
    </div>
</body>
</html>