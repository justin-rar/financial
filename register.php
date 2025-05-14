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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Register Page</title>
</head>
<style>
    body {
        height: 100vh;
        display: flex;
        align-items: center;
        background-color: #f5f5f5;
    }

    .form-login {
        max-width: 330px;
        margin: auto;
    }
</style>

<script type="text/javascript">
    function validateForm() {
        var password = document.getElementById("password").value;
        var confirm_password = document.getElementById("confirm_password").value;
        if (password !== confirm_password) {
            alert('Password and Confirm Password do not macth');
            return false;
        }
        return true;
    }
</script>

<body>
    <div class="container-fluid">
        <form class="form-login" onsubmit="return validateForm()" method="post" action="regist.php">
            <div class="AA">
                <div class="fw-normal mb-3">
                    <h3 style="text-align: center;">Register</h3>
                </div>
                <?php
                if (isset($_SESSION['log'])) {
                ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Failed!</strong> Username already exist.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                session_unset();
                }
                ?>
                <input type="text" class="form-control mb-2" name="username" placeholder="Username" required>
                <input id="password" type="password" class="form-control mb-2" name="password" placeholder="Password" required>
                <input id="confirm_password" type="password" class="form-control mb-2" name="confirm_password" placeholder="Confirm Password" required>
                <button class="btn btn-primary w-100" type="submit"><i class="bi bi-pencil-square"></i> Register</button>
                <p>Have an account already ? <a href="index.php">Enter</a></p>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>