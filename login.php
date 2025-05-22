<?php
require('connect.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM tb_account WHERE username = '$username'";
    $sql = mysqli_query($conn, $query);
    $result = mysqli_fetch_assoc($sql);


    if ($result['verify_status'] == '1') {
        if ($username && password_verify($password, $result['password'])) {
            $_SESSION['username'] = $username;
            header('Location: dashboard.php');
        } else {
            echo "<script>
        alert('Username and Password do not exist or incorrect!');
        window.location.href = 'loginPage.php';
    </script>";
        }
    } else {
        $_SESSION['status'] = "silahkan verifikasi email!";
        header('Location: loginPage.php');
    }
}
