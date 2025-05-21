<?php
require('connect.php');
session_start();

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $code = $_SESSION['code'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "SELECT * FROM tb_account WHERE reset_pascode = '$code'";
    $sql = mysqli_query($conn, $query);
    $result = mysqli_fetch_assoc($sql);

    if($result){
        $query = "UPDATE tb_account SET password = '$password', reset_pascode = '0' WHERE reset_pascode = '$code'";
        if($conn->query($query) === TRUE){
            session_unset();
            session_destroy();
            session_start();
            $_SESSION['status'] = "Sandi berhasil diatur ulang!";
            header("Location: loginPage.php");
            exit();
        } else {
            $_SESSION['status'] = "Proses gagal!";
            header("Location: loginPage.php");
            exit();
        }
    } else {
        $_SESSION['status'] = "Proses gagal!";
        header("Location: forgot_pass.php");
        exit();
    }
    $conn->close();
}
?>