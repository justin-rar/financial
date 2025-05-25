<?php
session_start();
if (isset($_SESSION["username"])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/loginStyle.css">
    <style>
        /* Additional styling to match loginStyle.css */
        .container.reset-password {
            height: auto;
            min-height: 500px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .reset-password form {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            padding: 0 20px;
        }
        

    </style>
</head>

<body>
    <div class="container reset-password" id="container">
        <div class="form-container">
            <form action="proses_reset.php" method="post">
                <h1>Ganti Password</h1>
                <p>Masukan password baru kamu</p>
                <?php
                if (isset($_SESSION['log'])) {
                    echo "<script>
                            alert('".$_SESSION['log']."');
                            window.location.href = 'loginPage.php';
                          </script>";
                    unset($_SESSION['log']);
                }
                ?>
                <div class="infield">
                    <input type="password" placeholder=" " name="password" id="password" required />
                    <label></label>
                </div>
                <button type="submit">Kirim</button>
                <p style="margin-top: 15px;"><a href="loginPage.php">Back to Login</a></p>
            </form>
        </div>
    </div>
</body>
</html>