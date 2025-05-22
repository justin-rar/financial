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
</head>

<body>

    <div class="container" id="container">
        <div class="form-container">
            <form action="verify_resetpass.php" method="post">
                <h1>Kode Reset</h1>
                <p>masukan kode reset yang telah dikirim dari email kamu</p>

                <?php
                if (isset($_SESSION['status'])) {
                    echo "<script>alert('" . $_SESSION['status'] . "');</script>";
                    unset($_SESSION['status']);
                }
                ?>


                <div class="infield">
                    <input type="text" name="code" placeholder="masukan kode" required />
                    <label></label>
                </div>

                <button type="submit">Send</button>
                <p style="margin-top: 15px;"><a href="loginPage.php">Back to Login</a></p>
            </form>
        </div>
    </div>

</body>

</html>