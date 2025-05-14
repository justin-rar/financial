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
    <title>Login Page</title>
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

<body>
    <div class="container-fluid">
        <form class="form-login" action="login.php" method="post">
            <div class="AA">
                <div class="fw-normal mb-3">
                    <h3 style="text-align: center;">Login First</h3>
                </div>
                <div class="form-floating mb-2">
                    <input type="text" class="form-control" name="username" placeholder="Username" required>
                    <label>Username</label>
                </div>
                <div class="form-floating mb-2">
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                    <label>Password</label>
                </div>
                <button class="btn btn-primary w-100" type="submit"> <i class="bi bi-box-arrow-in-right"></i> Submit</button>
                <p>Doesn't have an account ? <a href="register.php">Register</a></p>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>