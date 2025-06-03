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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/loginStyle.css">
    <style>
       

.container.forgot-password {
    height: auto;
    min-height: 500px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.forgot-password form {
    width: 100%;
    max-width: 400px;
    margin: 0 auto;
    padding: 0 20px;
}


    </style>
</head>

<body>
    <div class="container forgot-password" id="container">
        <div class="form-container">
            <form action="sent_resetpass.php" method="post">
                <h1>Lupa Password</h1>
                <p>Masukkan emailmu untuk mengatur ulang password</p>

                <?php
                if (isset($_SESSION['status'])) {
                    echo "<script>alert('" . $_SESSION['status'] . "');</script>";
                    unset($_SESSION['status']);
                }
                ?>

                <div class="infield">
                    <input type="email" name="email" placeholder="Email" required />
                    <label></label>
                </div>

                <button type="submit">Kirim Link Reset</button>
                <p style="margin-top: 15px;"><a href="loginPage.php">Kembali ke Login</a></p>
            </form>
        </div>
    </div>
</body>

</html>
