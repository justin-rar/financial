<?php
require('connect.php');
session_start();
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $verify_query = "SELECT verify_token, verify_status FROM tb_account WHERE verify_token = '$token' LIMIT 1 ";
    $verify_sql = mysqli_query($conn, $verify_query);
    $result = mysqli_fetch_assoc($verify_sql);

    if ($result) {
        if ($result['verify_status'] == '0') {
            $cliced_token = $result['verify_token'];
            $update_query = "UPDATE tb_account SET verify_status = '1' WHERE verify_token = '$cliced_token' ";
            $update_sql = mysqli_query($conn, $update_query);
            if ($update_sql) {
                $_SESSION['status'] = "email berhasil diverifikasi!";
                header('Location: loginPage.php');
            }
        } else {
            $_SESSION['status'] = "email sudah diverifikasi sebelumnya!";
            header('Location: loginPage.php');
        }
    } else {
        $_SESSION['status'] = "token tidak berlaku!";
        header('Location: loginPage.php');
    }
} else {
    $_SESSION['status'] = "token tidak berlaku!";
    header('Location: loginPage.php');
}
