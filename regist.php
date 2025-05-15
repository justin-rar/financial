<?php
session_start();
require('connect.php');

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $confirm_password = password_hash($_POST['confirm_password'], PASSWORD_DEFAULT);

    $query = "SELECT * FROM tb_account WHERE username = '$username'";
    $sql = mysqli_query($conn, $query);
    $result = mysqli_fetch_assoc($sql);

    if ($result) {
        $_SESSION['log'] = "username already used!";
        header('Location: index.php');
    } else {
        $query = "INSERT INTO tb_account(username, password) VALUES ('$username', '$password')";
        if ($conn->query($query) === TRUE) {
            echo "<script>
        alert('Account succesfully added. please login');
        window.location.href = 'index.php';
    </script>";
        }
    }
    $conn->close();
}
